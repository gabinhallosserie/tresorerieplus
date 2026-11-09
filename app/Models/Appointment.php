<?php

namespace App\Models;

use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['starts_at', 'ends_at', 'mode', 'name', 'email', 'phone', 'company', 'activity', 'message'])]
class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CANCELLED = 'cancelled';

    public const MODES = [
        'office' => 'Au cabinet (Millau)',
        'phone' => 'Par téléphone',
        'video' => 'En visioconférence',
    ];

    public const ACTIVITIES = [
        'tpe' => 'TPE / PME',
        'artisan' => 'Artisan',
        'commerce' => 'Commerçant',
        'liberal' => 'Profession libérale',
        'creation' => 'Entreprise en création',
        'other' => 'Autre',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'active_slot' => 'datetime',
            'reminder_sent_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function scopeConfirmed(Builder $query): void
    {
        $query->where('status', self::STATUS_CONFIRMED);
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isUpcoming(): bool
    {
        return ! $this->isCancelled() && $this->starts_at->isFuture();
    }

    public function cancel(): void
    {
        $this->status = self::STATUS_CANCELLED;
        $this->active_slot = null;
        $this->cancelled_at = now();
        $this->save();
    }

    public function modeLabel(): string
    {
        return self::MODES[$this->mode];
    }

    public function activityLabel(): string
    {
        return self::ACTIVITIES[$this->activity];
    }
}
