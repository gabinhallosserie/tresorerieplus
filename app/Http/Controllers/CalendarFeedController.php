<?php

namespace App\Http\Controllers;

use App\Booking\IcsCalendar;
use App\Models\Appointment;
use Illuminate\Http\Response;

class CalendarFeedController extends Controller
{
    public function __invoke(string $token): Response
    {
        $expectedToken = (string) config('site.calendar_feed_token');

        abort_if($expectedToken === '' || ! hash_equals($expectedToken, $token), 404);

        $appointments = Appointment::query()
            ->where('starts_at', '>=', now()->subMonths(3))
            ->orderBy('starts_at')
            ->get();

        return response(IcsCalendar::render($appointments, forAdvisor: true), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="rendez-vous.ics"',
        ]);
    }
}
