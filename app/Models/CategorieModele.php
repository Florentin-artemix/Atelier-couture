<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieModele extends Model
{
    use HasFactory;

    protected $table = 'categorie_modeles';

    protected $fillable = [
        'nom',
        'description',
        'slug',
        'image',
        'ordre_affichage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'ordre_affichage' => 'integer',
        ];
    }

    // Relations

    public function modeles(): HasMany
    {
        return $this->hasMany(Modele::class, 'categorie_modele_id');
    }

    public function mesureTypes(): BelongsToMany
    {
        return $this->belongsToMany(MesureType::class, 'categorie_mesure_types', 'categorie_modele_id', 'mesure_type_id')
                    ->withPivot('is_obligatoire');
    }

    public function mesureTypesObligatoires(): BelongsToMany
    {
        return $this->mesureTypes()->wherePivot('is_obligatoire', true);
    }

    public function realisations(): HasMany
    {
        return $this->hasMany(RealisationPortfolio::class, 'categorie_modele_id');
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre_affichage');
    }
}
