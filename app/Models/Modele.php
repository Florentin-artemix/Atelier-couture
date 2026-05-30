<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modele extends Model
{
    use HasFactory;

    protected $table = 'modeles';

    protected $fillable = [
        'categorie_modele_id',
        'nom',
        'description',
        'slug',
        'prix_base',
        'coefficient_complexite',
        'duree_estimee_jours',
        'image_principale',
        'images_supplementaires',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'prix_base' => 'decimal:2',
            'coefficient_complexite' => 'decimal:2',
            'duree_estimee_jours' => 'integer',
            'images_supplementaires' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Relations

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieModele::class, 'categorie_modele_id');
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'modele_id');
    }

    public function realisations(): HasMany
    {
        return $this->hasMany(RealisationPortfolio::class, 'modele_id');
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_principale) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk(config('ateliercouture.images_disk', 'public'))
            ->url($this->image_principale);
    }

    public function getPrixBaseCalcule(): float
    {
        return (float) $this->prix_base * (float) $this->coefficient_complexite;
    }
}
