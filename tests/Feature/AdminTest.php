<?php

namespace Tests\Feature;

use App\Mail\AppointmentCancelled;
use App\Models\Appointment;
use App\Models\BookingSettings;
use App\Models\OpeningHour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        foreach (['admin.dashboard', 'admin.availability.edit', 'admin.messages.index'] as $route) {
            $this->get(route($route))->assertRedirect(route('admin.login'));
        }
    }

    public function test_admin_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'secret-password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_dashboard_shows_the_week_appointments(): void
    {
        $this->travelTo(now()->setDate(2026, 11, 9)->setTime(8, 0));
        Appointment::factory()->at('2026-11-12 10:00')->create(['name' => 'Client Visible']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Client Visible');
    }

    public function test_admin_can_update_availability(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.availability.update'), [
                'slot_minutes' => 45,
                'buffer_minutes' => 10,
                'min_notice_hours' => 48,
                'max_days_ahead' => 60,
                'hours' => [
                    1 => [['starts_at' => '08:30', 'ends_at' => '12:00'], ['starts_at' => '', 'ends_at' => '']],
                    3 => [['starts_at' => '14:00', 'ends_at' => '17:30']],
                ],
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(45, BookingSettings::current()->slot_minutes);
        $this->assertSame([1, 3], OpeningHour::query()->orderBy('weekday')->pluck('weekday')->all());
    }

    public function test_availability_rejects_inverted_ranges(): void
    {
        OpeningHour::create(['weekday' => 1, 'starts_at' => '09:00', 'ends_at' => '12:00']);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.availability.update'), [
                'slot_minutes' => 60,
                'buffer_minutes' => 0,
                'min_notice_hours' => 24,
                'max_days_ahead' => 30,
                'hours' => [2 => [['starts_at' => '12:00', 'ends_at' => '09:00']]],
            ])
            ->assertSessionHasErrors('hours');

        $this->assertSame(1, OpeningHour::count());
    }

    public function test_admin_can_cancel_and_notify_client(): void
    {
        Mail::fake();
        $appointment = Appointment::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('admin.appointments.cancel', $appointment), ['notify_client' => '1']);

        $this->assertTrue($appointment->fresh()->isCancelled());
        Mail::assertSent(AppointmentCancelled::class, fn ($mail) => $mail->hasTo($appointment->email));
    }

    public function test_calendar_feed_requires_the_secret_token(): void
    {
        config(['site.calendar_feed_token' => 'secret-token']);
        Appointment::factory()->create(['name' => 'Client Agenda']);

        $this->get(route('calendar.feed', 'mauvais'))->assertNotFound();
        $this->get(route('calendar.feed', 'secret-token'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->assertSee('SUMMARY:RDV Client Agenda', false);
    }
}
