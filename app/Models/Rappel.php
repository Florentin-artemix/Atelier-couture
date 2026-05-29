<?php

namespace App\Models;

use App\Enums\ReminderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rappel extends Model
{
    use HasFactory;

    protected $table = 'rappels';

    protected $fillable = [
        'commande_id',
        'client_id',
        'type',
        'titre',
        'description',
        'date_echeance',
        'is_fait',
        'date_fait',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReminderType::class,
            'date_echeance' => 'date',
            'is_fait' => 'boolean',
            'date_fait' => 'datetime',
        ];
    }

    // Relations

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Scopes

    public function scopePending($query)
    {
        return $query->where('is_fait', false);
    }

    public function scopeOverdue($query)
    {
        return $query->pending()->where('date_echeance', '<', now()->toDateString());
    }

    public function scopeForCommande($query, int $commandeId)
    {
        return $query->where('commande_id', $commandeId);
    }

    public function scopeDueToday($query)
    {
        return $query->pending()->where('date_echeance', now()->toDateString());
    }

    public function scopeUpcoming($query, int $days = 7)
    {
        return $query->pending()
                     ->whereBetween('date_echeance', [
                         now()->toDateString(),
                         now()->addDays($days)->toDateString(),
                     ]);
    }

    // Helpers

    public function markAsDone(): void
    {
        $this->update([
            'is_fait' => true,
            'date_fait' => now(),
        ]);
    }
}
