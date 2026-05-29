<?php

namespace App\Enums;

enum ReminderType: string
{
    case Automatique = 'automatique';
    case Manuel = 'manuel';

    public function label(): string
    {
        return match ($this) {
            self::Automatique => 'Automatique',
            self::Manuel => 'Manuel',
        };
    }
}
