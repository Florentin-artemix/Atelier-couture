<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MesureType extends Model
{
    use HasFactory;

    protected $table = 'mesure_types';

    protected $fillable = [
        'nom',
        'libelle',
        'unite',
        'description',
        'valeur_min',
        'valeur_max',
        'is_base',
        'ordre_affichage',
    ];

    protected function casts(): array
    {
        return [
            'valeur_min' => 'decimal:2',
            'valeur_max' => 'decimal:2',
            'is_base' => 'boolean',
            'ordre_affichage' => 'integer',
        ];
    }

    // Relations

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CategorieModele::class, 'categorie_mesure_types', 'mesure_type_id', 'categorie_modele_id')
                    ->withPivot('is_obligatoire');
    }

    public function mesuresClients(): HasMany
    {
        return $this->hasMany(MesureClient::class, 'mesure_type_id');
    }

    // Scopes

    public function scopeBase($query)
    {
        return $query->where('is_base', true);
    }

    public function scopeSpecific($query)
    {
        return $query->where('is_base', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre_affichage');
    }
}
