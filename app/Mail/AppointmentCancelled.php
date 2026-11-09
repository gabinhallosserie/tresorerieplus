<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AppointmentCancelled extends Mailable
{
    public function __construct(public Appointment $appointment, public bool $forAdvisor) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->forAdvisor
            ? 'Rendez-vous annulé : '.$this->appointment->name
            : 'Votre rendez-vous Trésorerie Plus est annulé');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.appointment-cancelled');
    }
}
