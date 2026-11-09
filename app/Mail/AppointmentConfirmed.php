<?php

namespace App\Mail;

use App\Booking\IcsCalendar;
use App\Models\Appointment;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AppointmentConfirmed extends Mailable
{
    public function __construct(public Appointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre rendez-vous Trésorerie Plus est confirmé');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.appointment-confirmed');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => IcsCalendar::render([$this->appointment], forAdvisor: false), 'rendez-vous.ics')
                ->withMime('text/calendar'),
        ];
    }
}
