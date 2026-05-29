<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MesureClient extends Model
{
    use HasFactory;

    protected $table = 'mesure_clients';

    protected $fillable = [
        'client_id',
        'mesure_type_id',
        'commande_id',
        'valeur',
        'date_prise',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'valeur' => 'decimal:2',
            'date_prise' => 'date',
        ];
    }

    // Relations

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function mesureType(): BelongsTo
    {
        return $this->belongsTo(MesureType::class, 'mesure_type_id');
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    // Scopes

    public function scopeGenerales($query)
    {
        return $query->whereNull('commande_id');
    }

    public function scopeForCommande($query, int $commandeId)
    {
        return $query->where('commande_id', $commandeId);
    }
}
