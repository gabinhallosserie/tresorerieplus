<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slot_minutes', 'buffer_minutes', 'min_notice_hours', 'max_days_ahead'])]
class BookingSettings extends Model
{
    protected $attributes = [
        'slot_minutes' => 60,
        'buffer_minutes' => 15,
        'min_notice_hours' => 24,
        'max_days_ahead' => 45,
    ];

    protected function casts(): array
    {
        return [
            'slot_minutes' => 'integer',
            'buffer_minutes' => 'integer',
            'min_notice_hours' => 'integer',
            'max_days_ahead' => 'integer',
        ];
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate([]);
    }
}
