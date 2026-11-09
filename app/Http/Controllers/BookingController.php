<?php

namespace App\Http\Controllers;

use App\Booking\SlotFinder;
use App\Mail\AppointmentBooked;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentConfirmed;
use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    private const DAYS_PER_PAGE = 7;

    public function index(Request $request, SlotFinder $slotFinder): View
    {
        $firstDay = $slotFinder->firstBookableDay();
        $lastDay = $slotFinder->lastBookableDay();

        $requestedStart = rescue(
            fn () => CarbonImmutable::createFromFormat('!Y-m-d', (string) $request->query('du')),
            null,
            report: false,
        );
        $periodStart = $requestedStart ? $requestedStart->max($firstDay)->min($lastDay) : $firstDay;
        $periodEnd = $periodStart->addDays(self::DAYS_PER_PAGE - 1);

        $slots = $slotFinder->between($periodStart, $periodEnd);

        $days = collect(range(0, self::DAYS_PER_PAGE - 1))
            ->map(fn (int $offset) => $periodStart->addDays($offset))
            ->filter(fn (CarbonImmutable $day) => $day->lte($lastDay));

        $previousStart = $periodStart->gt($firstDay) ? $periodStart->subDays(self::DAYS_PER_PAGE)->max($firstDay) : null;
        $nextStart = $periodEnd->lt($lastDay) ? $periodEnd->addDay() : null;

        return view('booking.index', [
            'days' => $days,
            'slots' => $slots,
            'previousStart' => $previousStart,
            'nextStart' => $nextStart,
            'slotMinutes' => $slotFinder->slotMinutes(),
        ]);
    }

    public function create(Request $request, SlotFinder $slotFinder): View|RedirectResponse
    {
        $start = $this->parseSlot((string) $request->query('creneau'));

        if (! $start || ! $slotFinder->isAvailable($start)) {
            return to_route('booking.index')->with('warning', 'Ce créneau n\'est plus disponible. Merci d\'en choisir un autre.');
        }

        return view('booking.create', [
            'start' => $start,
            'end' => $start->addMinutes($slotFinder->slotMinutes()),
        ]);
    }

    public function store(Request $request, SlotFinder $slotFinder): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return to_route('booking.index');
        }

        $validated = $request->validate([
            'starts_at' => ['required', 'date_format:Y-m-d H:i'],
            'mode' => ['required', Rule::in(array_keys(Appointment::MODES))],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['required', 'string', 'regex:/^[0-9+().\s-]{10,20}$/'],
            'company' => ['nullable', 'string', 'max:150'],
            'activity' => ['required', Rule::in(array_keys(Appointment::ACTIVITIES))],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
        ]);

        $start = CarbonImmutable::createFromFormat('!Y-m-d H:i', $validated['starts_at']);

        if (! $slotFinder->isAvailable($start)) {
            return to_route('booking.index')->with('warning', 'Ce créneau vient d\'être réservé. Merci d\'en choisir un autre.');
        }

        $appointment = new Appointment([
            ...collect($validated)->except(['starts_at', 'consent'])->all(),
            'starts_at' => $start,
            'ends_at' => $start->addMinutes($slotFinder->slotMinutes()),
        ]);
        $appointment->status = Appointment::STATUS_CONFIRMED;
        $appointment->active_slot = $start;
        $appointment->token = Str::random(48);

        try {
            $appointment->save();
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'starts_at' => 'Ce créneau vient d\'être réservé. Merci d\'en choisir un autre.',
            ])->redirectTo(route('booking.index'));
        }

        rescue(fn () => Mail::to($appointment->email)->send(new AppointmentConfirmed($appointment)));
        rescue(fn () => Mail::to(config('site.notification_email'))->send(new AppointmentBooked($appointment)));

        return to_route('booking.show', $appointment->token)->with('booked', true);
    }

    public function show(string $token): View
    {
        $appointment = Appointment::query()->where('token', $token)->firstOrFail();

        return view('booking.show', ['appointment' => $appointment]);
    }

    public function cancel(string $token): RedirectResponse
    {
        $appointment = Appointment::query()->where('token', $token)->firstOrFail();

        if (! $appointment->isUpcoming()) {
            return to_route('booking.show', $token);
        }

        $appointment->cancel();

        rescue(fn () => Mail::to($appointment->email)->send(new AppointmentCancelled($appointment, forAdvisor: false)));
        rescue(fn () => Mail::to(config('site.notification_email'))->send(new AppointmentCancelled($appointment, forAdvisor: true)));

        return to_route('booking.show', $token)->with('status', 'Votre rendez-vous a bien été annulé.');
    }

    private function parseSlot(string $value): ?CarbonImmutable
    {
        return rescue(fn () => CarbonImmutable::createFromFormat('!Y-m-d H:i', $value), null, report: false) ?: null;
    }
}
