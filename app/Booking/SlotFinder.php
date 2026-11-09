<?php

namespace App\Booking;

use App\Models\Appointment;
use App\Models\BookingSettings;
use App\Models\ClosedPeriod;
use App\Models\OpeningHour;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class SlotFinder
{
    private BookingSettings $settings;

    public function __construct()
    {
        $this->settings = BookingSettings::current();
    }

    public function slotMinutes(): int
    {
        return $this->settings->slot_minutes;
    }

    public function firstBookableDay(): CarbonImmutable
    {
        return $this->earliestStart()->startOfDay();
    }

    public function lastBookableDay(): CarbonImmutable
    {
        return CarbonImmutable::today()->addDays($this->settings->max_days_ahead);
    }

    /**
     * @return array<string, list<CarbonImmutable>> Free slots keyed by Y-m-d, days without slots omitted.
     */
    public function between(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $from = $from->startOfDay()->max($this->firstBookableDay());
        $to = $to->startOfDay()->min($this->lastBookableDay());

        if ($from->gt($to)) {
            return [];
        }

        $openingHours = OpeningHour::query()->orderBy('starts_at')->get()->groupBy('weekday');

        $closedPeriods = ClosedPeriod::query()
            ->where('ends_on', '>=', $from)
            ->where('starts_on', '<=', $to)
            ->get();

        $booked = Appointment::query()
            ->confirmed()
            ->where('ends_at', '>', $from)
            ->where('starts_at', '<', $to->endOfDay())
            ->get(['starts_at', 'ends_at']);

        $earliestStart = $this->earliestStart();
        $slots = [];

        for ($day = $from; $day->lte($to); $day = $day->addDay()) {
            if ($this->isClosed($day, $closedPeriods)) {
                continue;
            }

            foreach ($openingHours->get($day->dayOfWeekIso, []) as $openingHour) {
                foreach ($this->slotsWithin($day, $openingHour) as $start) {
                    if ($start->lt($earliestStart) || $this->overlapsBooking($start, $booked)) {
                        continue;
                    }

                    $slots[$day->toDateString()][] = $start;
                }
            }
        }

        return $slots;
    }

    public function isAvailable(CarbonImmutable $start): bool
    {
        $slotsOfDay = $this->between($start, $start)[$start->toDateString()] ?? [];

        return collect($slotsOfDay)->contains(fn (CarbonImmutable $slot) => $slot->equalTo($start));
    }

    private function earliestStart(): CarbonImmutable
    {
        return CarbonImmutable::now()->addHours($this->settings->min_notice_hours);
    }

    /**
     * @return list<CarbonImmutable>
     */
    private function slotsWithin(CarbonImmutable $day, OpeningHour $openingHour): array
    {
        $cursor = $day->setTimeFromTimeString($openingHour->starts_at);
        $closesAt = $day->setTimeFromTimeString($openingHour->ends_at);
        $step = $this->settings->slot_minutes + $this->settings->buffer_minutes;
        $starts = [];

        while ($cursor->addMinutes($this->settings->slot_minutes)->lte($closesAt)) {
            $starts[] = $cursor;
            $cursor = $cursor->addMinutes($step);
        }

        return $starts;
    }

    private function isClosed(CarbonImmutable $day, Collection $closedPeriods): bool
    {
        return $closedPeriods->contains(
            fn (ClosedPeriod $period) => $day->betweenIncluded($period->starts_on, $period->ends_on),
        );
    }

    private function overlapsBooking(CarbonImmutable $start, Collection $booked): bool
    {
        $buffer = $this->settings->buffer_minutes;
        $end = $start->addMinutes($this->settings->slot_minutes);

        return $booked->contains(
            fn (Appointment $appointment) => $start->lt($appointment->ends_at->copy()->addMinutes($buffer))
                && $end->gt($appointment->starts_at->copy()->subMinutes($buffer)),
        );
    }
}
