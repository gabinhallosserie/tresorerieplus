<?php

namespace App\Booking;

use App\Models\Appointment;

class IcsCalendar
{
    /**
     * @param  iterable<Appointment>  $appointments
     */
    public static function render(iterable $appointments, bool $forAdvisor): string
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Tresorerie Plus//Rendez-vous//FR',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:'.self::escape($forAdvisor ? 'Rendez-vous Trésorerie Plus' : 'Trésorerie Plus'),
        ];

        foreach ($appointments as $appointment) {
            $summary = $forAdvisor
                ? 'RDV '.$appointment->name.($appointment->company ? ' ('.$appointment->company.')' : '')
                : 'Rendez-vous Trésorerie Plus';

            $description = $forAdvisor
                ? implode("\n", array_filter([
                    $appointment->modeLabel(),
                    $appointment->phone,
                    $appointment->email,
                    $appointment->message,
                ]))
                : $appointment->modeLabel().' avec '.config('site.advisor.name').' – '.config('site.advisor.phone');

            $lines = [
                ...$lines,
                'BEGIN:VEVENT',
                'UID:appointment-'.$appointment->id.'@'.parse_url(config('app.url'), PHP_URL_HOST),
                'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
                'DTSTART:'.$appointment->starts_at->copy()->utc()->format('Ymd\THis\Z'),
                'DTEND:'.$appointment->ends_at->copy()->utc()->format('Ymd\THis\Z'),
                'SUMMARY:'.self::escape($summary),
                'DESCRIPTION:'.self::escape($description),
                'LOCATION:'.self::escape($appointment->mode === 'office' ? config('site.advisor.address_line') : $appointment->modeLabel()),
                'STATUS:'.($appointment->isCancelled() ? 'CANCELLED' : 'CONFIRMED'),
                'END:VEVENT',
            ];
        }

        $lines[] = 'END:VCALENDAR';

        return collect($lines)->map(fn (string $line) => self::fold($line))->implode("\r\n")."\r\n";
    }

    private static function escape(string $text): string
    {
        return str_replace(['\\', ';', ',', "\r\n", "\n"], ['\\\\', '\\;', '\\,', '\\n', '\\n'], $text);
    }

    // RFC 5545 limits content lines to 75 octets; continuation lines start with a space.
    private static function fold(string $line): string
    {
        $folded = '';

        while (strlen($line) > 75) {
            $chunk = mb_strcut($line, 0, 75);
            $folded .= $chunk."\r\n ";
            $line = substr($line, strlen($chunk));
        }

        return $folded.$line;
    }
}
