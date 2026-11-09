<?php

use App\Mail\AppointmentReminder;
use App\Models\Appointment;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('appointments:send-reminders', function () {
    $appointments = Appointment::query()
        ->confirmed()
        ->whereNull('reminder_sent_at')
        ->whereBetween('starts_at', [now(), now()->addDay()])
        ->get();

    foreach ($appointments as $appointment) {
        Mail::to($appointment->email)->send(new AppointmentReminder($appointment));

        $appointment->reminder_sent_at = now();
        $appointment->save();
    }

    $this->info($appointments->count().' rappel(s) envoyé(s).');
})->purpose('Envoie un rappel aux clients dont le rendez-vous a lieu dans les 24 heures');

Schedule::command('appointments:send-reminders')->hourly();
