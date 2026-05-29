<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Precommande = 'precommande';
    case Nouvelle = 'nouvelle';
    case EnAttenteMesures = 'en_attente_mesures';
    case EnProduction = 'en_production';
    case Prete = 'prete';
    case Livree = 'livree';
    case Annulee = 'annulee';

    public function label(): string
    {
        return match ($this) {
            self::Precommande => 'Precommande',
            self::Nouvelle => 'Nouvelle',
            self::EnAttenteMesures => 'En attente de mesures',
            self::EnProduction => 'En production',
            self::Prete => 'Prete',
            self::Livree => 'Livree',
            self::Annulee => 'Annulee',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Precommande => 'purple',
            self::Nouvelle => 'blue',
            self::EnAttenteMesures => 'yellow',
            self::EnProduction => 'indigo',
            self::Prete => 'green',
            self::Livree => 'gray',
            self::Annulee => 'red',
        };
    }

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Precommande => [self::Nouvelle, self::Annulee],
            self::Nouvelle => [self::EnAttenteMesures, self::EnProduction, self::Annulee],
            self::EnAttenteMesures => [self::EnProduction, self::Annulee],
            self::EnProduction => [self::Prete, self::Annulee],
            self::Prete => [self::Livree, self::Annulee],
            self::Livree => [],
            self::Annulee => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions());
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Livree, self::Annulee]);
    }
}
