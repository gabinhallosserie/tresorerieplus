<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_reachable(): void
    {
        foreach (['home', 'method', 'network', 'contact', 'legal', 'privacy', 'booking.index'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_visitor_can_send_a_message(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), $this->payload())
            ->assertRedirect(route('contact'))
            ->assertSessionHas('status');

        $message = ContactMessage::sole();
        $this->assertSame('diagnostic', $message->subject);
        Mail::assertSent(ContactMessageReceived::class, fn ($mail) => $mail->hasTo(config('site.notification_email')));
    }

    public function test_message_requires_consent_and_content(): void
    {
        $this->post(route('contact.store'), $this->payload(['consent' => null, 'message' => '']))
            ->assertSessionHasErrors(['consent', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_honeypot_submissions_are_silently_dropped(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), $this->payload(['website' => 'http://spam.example']))
            ->assertRedirect(route('contact'));

        $this->assertSame(0, ContactMessage::count());
        Mail::assertNothingSent();
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return [
            'name' => 'Paul Durand',
            'email' => 'paul@example.com',
            'phone' => '',
            'subject' => 'diagnostic',
            'message' => 'Bonjour, je souhaite faire le point sur ma trésorerie.',
            'consent' => '1',
            ...$overrides,
        ];
    }
}
