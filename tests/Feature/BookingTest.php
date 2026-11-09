<?php

namespace Tests\Feature;

use App\Booking\SlotFinder;
use App\Mail\AppointmentBooked;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentReminder;
use App\Models\Appointment;
use App\Models\BookingSettings;
use App\Models\ClosedPeriod;
use App\Models\OpeningHour;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(CarbonImmutable::parse('2026-11-09 08:00'));
        Mail::fake();

        BookingSettings::current()->update([
            'slot_minutes' => 60,
            'buffer_minutes' => 15,
            'min_notice_hours' => 24,
            'max_days_ahead' => 30,
        ]);
        OpeningHour::create(['weekday' => 4, 'starts_at' => '09:00', 'ends_at' => '12:00']);
    }

    public function test_slots_follow_opening_hours_duration_and_buffer(): void
    {
        $slots = app(SlotFinder::class)->between(CarbonImmutable::parse('2026-11-12'), CarbonImmutable::parse('2026-11-12'));

        $this->assertSame(['09:00', '10:15'], collect($slots['2026-11-12'])->map->format('H:i')->all());
    }

    public function test_slots_respect_minimum_notice(): void
    {
        OpeningHour::create(['weekday' => 2, 'starts_at' => '09:00', 'ends_at' => '12:00']);

        $slots = app(SlotFinder::class)->between(CarbonImmutable::parse('2026-11-10'), CarbonImmutable::parse('2026-11-10'));

        $this->assertSame(['09:00', '10:15'], collect($slots['2026-11-10'])->map->format('H:i')->all());

        $this->travelTo(CarbonImmutable::parse('2026-11-09 09:30'));
        $slots = app(SlotFinder::class)->between(CarbonImmutable::parse('2026-11-10'), CarbonImmutable::parse('2026-11-10'));

        $this->assertSame(['10:15'], collect($slots['2026-11-10'])->map->format('H:i')->all());
    }

    public function test_closed_periods_and_booked_slots_are_not_offered(): void
    {
        Appointment::factory()->at('2026-11-12 09:00')->create();
        ClosedPeriod::create(['starts_on' => '2026-11-19', 'ends_on' => '2026-11-19']);

        $slots = app(SlotFinder::class)->between(CarbonImmutable::parse('2026-11-12'), CarbonImmutable::parse('2026-11-19'));

        $this->assertSame(['10:15'], collect($slots['2026-11-12'])->map->format('H:i')->all());
        $this->assertArrayNotHasKey('2026-11-19', $slots);
    }

    public function test_cancelled_appointments_free_their_slot(): void
    {
        Appointment::factory()->at('2026-11-12 09:00')->cancelled()->create();

        $this->assertTrue(app(SlotFinder::class)->isAvailable(CarbonImmutable::parse('2026-11-12 09:00')));
    }

    public function test_client_can_book_a_free_slot(): void
    {
        $response = $this->post(route('booking.store'), $this->bookingPayload());

        $appointment = Appointment::sole();
        $response->assertRedirect(route('booking.show', $appointment->token));
        $this->assertSame('2026-11-12 09:00:00', $appointment->starts_at->toDateTimeString());
        $this->assertSame('2026-11-12 10:00:00', $appointment->ends_at->toDateTimeString());
        $this->assertSame(Appointment::STATUS_CONFIRMED, $appointment->status);

        Mail::assertSent(AppointmentConfirmed::class, fn ($mail) => $mail->hasTo('client@example.com'));
        Mail::assertSent(AppointmentBooked::class, fn ($mail) => $mail->hasTo(config('site.notification_email')));
    }

    public function test_a_slot_cannot_be_booked_twice(): void
    {
        Appointment::factory()->at('2026-11-12 09:00')->create();

        $this->post(route('booking.store'), $this->bookingPayload())
            ->assertRedirect(route('booking.index'))
            ->assertSessionHas('warning');

        $this->assertSame(1, Appointment::count());
    }

    public function test_slot_outside_opening_hours_is_rejected(): void
    {
        $this->post(route('booking.store'), $this->bookingPayload(['starts_at' => '2026-11-12 15:00']))
            ->assertRedirect(route('booking.index'));

        $this->assertSame(0, Appointment::count());
    }

    public function test_booking_requires_consent_and_valid_contact_details(): void
    {
        $this->post(route('booking.store'), $this->bookingPayload(['consent' => null, 'email' => 'pas-un-email']))
            ->assertSessionHasErrors(['consent', 'email']);

        $this->assertSame(0, Appointment::count());
    }

    public function test_client_can_cancel_with_the_link_from_the_email(): void
    {
        $appointment = Appointment::factory()->at('2026-11-12 09:00')->create();

        $this->post(route('booking.cancel', $appointment->token))->assertRedirect(route('booking.show', $appointment->token));

        $this->assertTrue($appointment->fresh()->isCancelled());
        $this->assertNull($appointment->fresh()->active_slot);
        Mail::assertSent(AppointmentCancelled::class, 2);
    }

    public function test_unknown_booking_token_returns_404(): void
    {
        $this->get(route('booking.show', 'inconnu'))->assertNotFound();
    }

    public function test_booking_page_lists_free_slots(): void
    {
        $this->get(route('booking.index'))
            ->assertOk()
            ->assertSee(route('booking.create', ['creneau' => '2026-11-12 09:00']), false);
    }

    public function test_reminders_are_sent_once_for_appointments_within_24_hours(): void
    {
        $tomorrow = Appointment::factory()->at('2026-11-10 07:00')->create();
        Appointment::factory()->at('2026-11-12 09:00')->create();
        Appointment::factory()->at('2026-11-10 06:00')->cancelled()->create();

        $this->artisan('appointments:send-reminders')->assertSuccessful();
        $this->artisan('appointments:send-reminders')->assertSuccessful();

        Mail::assertSent(AppointmentReminder::class, 1);
        Mail::assertSent(AppointmentReminder::class, fn ($mail) => $mail->appointment->is($tomorrow));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function bookingPayload(array $overrides = []): array
    {
        return [
            'starts_at' => '2026-11-12 09:00',
            'mode' => 'office',
            'name' => 'Jeanne Martin',
            'email' => 'client@example.com',
            'phone' => '06 12 34 56 78',
            'company' => 'Atelier Martin',
            'activity' => 'artisan',
            'message' => 'Besoin d\'aide pour mon prévisionnel.',
            'consent' => '1',
            ...$overrides,
        ];
    }
}
