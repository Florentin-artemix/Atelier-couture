<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Accessoire extends Model
{
    use HasFactory;

    protected $table = 'accessoires';

    protected $fillable = [
        'nom',
        'description',
        'prix_unitaire',
        'unite',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'prix_unitaire' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relations

    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class, 'commande_accessoires', 'accessoire_id', 'commande_id')
                    ->withPivot(['quantite', 'prix_unitaire_snapshot', 'fourni_par_client'])
                    ->withTimestamps();
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
