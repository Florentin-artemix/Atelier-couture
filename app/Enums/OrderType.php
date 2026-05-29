<?php

namespace App\Enums;

enum OrderType: string
{
    case Physique = 'physique';
    case ADistance = 'a_distance';
    case Precommande = 'precommande';

    public function label(): string
    {
        return match ($this) {
            self::Physique => 'Physique (en atelier)',
            self::ADistance => 'A distance',
            self::Precommande => 'Precommande en ligne',
        };
    }
}
