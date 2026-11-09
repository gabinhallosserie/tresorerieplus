<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentCancelled;
use App\Models\Appointment;
use App\Models\ContactMessage;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $requestedWeek = rescue(
            fn () => CarbonImmutable::createFromFormat('!Y-m-d', (string) $request->query('semaine')),
            null,
            report: false,
        );
        $weekStart = ($requestedWeek ?: CarbonImmutable::today())->startOfWeek();
        $weekEnd = $weekStart->endOfWeek();

        $weekAppointments = Appointment::query()
            ->whereBetween('starts_at', [$weekStart, $weekEnd])
            ->orderBy('starts_at')
            ->get()
            ->groupBy(fn (Appointment $appointment) => $appointment->starts_at->toDateString());

        $upcoming = Appointment::query()
            ->confirmed()
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', [
            'weekStart' => $weekStart,
            'weekAppointments' => $weekAppointments,
            'upcoming' => $upcoming,
            'unreadMessages' => ContactMessage::query()->whereNull('read_at')->count(),
            'calendarFeedUrl' => filled(config('site.calendar_feed_token'))
                ? route('calendar.feed', config('site.calendar_feed_token'))
                : null,
        ]);
    }

    public function show(Appointment $appointment): View
    {
        return view('admin.appointment', ['appointment' => $appointment]);
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $appointment->admin_note = $validated['admin_note'];
        $appointment->save();

        return back()->with('status', 'Note enregistrée.');
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        if (! $appointment->isUpcoming()) {
            return back();
        }

        $appointment->cancel();

        if ($request->boolean('notify_client')) {
            rescue(fn () => Mail::to($appointment->email)->send(new AppointmentCancelled($appointment, forAdvisor: false)));
        }

        return back()->with('status', 'Rendez-vous annulé. Le créneau est de nouveau disponible.');
    }
}
