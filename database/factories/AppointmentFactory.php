<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()->addDays(3)->setTime(10, 0);

        return [
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'mode' => 'office',
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '06 12 34 56 78',
            'company' => fake()->company(),
            'activity' => 'artisan',
            'message' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Appointment $appointment) {
            $appointment->status ??= Appointment::STATUS_CONFIRMED;
            $appointment->active_slot ??= $appointment->isCancelled() ? null : $appointment->starts_at;
            $appointment->token ??= Str::random(48);
        });
    }

    public function at(string $startsAt, int $minutes = 60): static
    {
        return $this->state(fn () => [
            'starts_at' => $startsAt,
            'ends_at' => now()->parse($startsAt)->addMinutes($minutes),
        ]);
    }

    public function cancelled(): static
    {
        return $this->afterMaking(function (Appointment $appointment) {
            $appointment->status = Appointment::STATUS_CANCELLED;
            $appointment->active_slot = null;
            $appointment->cancelled_at = now();
        });
    }
}
