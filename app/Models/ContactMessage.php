<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'phone', 'subject', 'message'])]
class ContactMessage extends Model
{
    public const SUBJECTS = [
        'information' => 'Demande d\'information',
        'diagnostic' => 'Diagnostic de trésorerie',
        'network' => 'Rejoindre le réseau',
        'other' => 'Autre',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function subjectLabel(): string
    {
        return self::SUBJECTS[$this->subject];
    }
}
