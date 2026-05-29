<?php

namespace App\Enums;

enum ReminderType: string
{
    case PreLivraison = 'pre_livraison';
    case Retard = 'retard';
    case Precommande = 'precommande';
    case Manuel = 'manuel';

    public function label(): string
    {
        return match ($this) {
            self::PreLivraison => 'Pre-livraison',
            self::Retard => 'Retard',
            self::Precommande => 'Precommande',
            self::Manuel => 'Manuel',
        };
    }
}
