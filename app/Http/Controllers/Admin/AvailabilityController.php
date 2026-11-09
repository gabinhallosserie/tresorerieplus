<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingSettings;
use App\Models\ClosedPeriod;
use App\Models\OpeningHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function edit(): View
    {
        return view('admin.availability', [
            'settings' => BookingSettings::current(),
            'openingHours' => OpeningHour::query()->orderBy('starts_at')->get()->groupBy('weekday'),
            'closedPeriods' => ClosedPeriod::query()
                ->where('ends_on', '>=', today())
                ->orderBy('starts_on')
                ->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slot_minutes' => ['required', 'integer', Rule::in([30, 45, 60, 90, 120])],
            'buffer_minutes' => ['required', 'integer', 'min:0', 'max:120'],
            'min_notice_hours' => ['required', 'integer', 'min:0', 'max:336'],
            'max_days_ahead' => ['required', 'integer', 'min:1', 'max:365'],
            'hours' => ['array'],
            'hours.*' => ['array'],
            'hours.*.*.starts_at' => ['nullable', 'required_with:hours.*.*.ends_at', 'date_format:H:i'],
            'hours.*.*.ends_at' => ['nullable', 'required_with:hours.*.*.starts_at', 'date_format:H:i'],
        ], [
            'hours.*.*.starts_at.required_with' => 'Indiquez l\'heure de début de chaque plage.',
            'hours.*.*.ends_at.required_with' => 'Indiquez l\'heure de fin de chaque plage.',
        ]);

        $ranges = collect($validated['hours'] ?? [])
            ->only(array_keys(OpeningHour::WEEKDAYS))
            ->flatMap(fn (array $dayRanges, int $weekday) => collect($dayRanges)
                ->filter(fn (array $range) => filled($range['starts_at'] ?? null))
                ->map(fn (array $range) => [
                    'weekday' => $weekday,
                    'starts_at' => $range['starts_at'],
                    'ends_at' => $range['ends_at'],
                ]))
            ->values();

        $invalidRange = $ranges->first(fn (array $range) => $range['ends_at'] <= $range['starts_at']);

        if ($invalidRange) {
            return back()->withInput()->withErrors([
                'hours' => OpeningHour::WEEKDAYS[$invalidRange['weekday']].' : l\'heure de fin doit être après l\'heure de début.',
            ]);
        }

        DB::transaction(function () use ($validated, $ranges) {
            BookingSettings::current()->update(collect($validated)->except('hours')->all());

            OpeningHour::query()->delete();
            $ranges->each(fn (array $range) => OpeningHour::create($range));
        });

        return back()->with('status', 'Disponibilités enregistrées.');
    }

    public function storeClosedPeriod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'starts_on' => ['required', 'date', 'after_or_equal:today'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'reason' => ['nullable', 'string', 'max:120'],
        ]);

        ClosedPeriod::create($validated);

        return back()->with('status', 'Période de fermeture ajoutée.');
    }

    public function destroyClosedPeriod(ClosedPeriod $closedPeriod): RedirectResponse
    {
        $closedPeriod->delete();

        return back()->with('status', 'Période de fermeture supprimée.');
    }
}
