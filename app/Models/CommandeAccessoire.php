<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandeAccessoire extends Model
{
    protected $table = 'commande_accessoires';

    public $timestamps = false;

    protected $fillable = [
        'commande_id',
        'accessoire_id',
        'quantite',
        'prix_unitaire_snapshot',
        'fourni_par_client',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
            'prix_unitaire_snapshot' => 'decimal:2',
            'fourni_par_client' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    // Relations

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function accessoire(): BelongsTo
    {
        return $this->belongsTo(Accessoire::class);
    }

    // Helpers

    public function getMontantTotal(): float
    {
        return (float) $this->prix_unitaire_snapshot * $this->quantite;
    }
}
