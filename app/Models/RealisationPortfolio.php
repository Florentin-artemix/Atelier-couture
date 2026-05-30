<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisationPortfolio extends Model
{
    use HasFactory;

    protected $table = 'realisation_portfolios';

    protected $fillable = [
        'titre',
        'description',
        'categorie_modele_id',
        'modele_id',
        'commande_id',
        'image_principale',
        'images_supplementaires',
        'date_realisation',
        'is_visible',
        'ordre_affichage',
    ];

    protected function casts(): array
    {
        return [
            'images_supplementaires' => 'array',
            'date_realisation' => 'date',
            'is_visible' => 'boolean',
            'ordre_affichage' => 'integer',
        ];
    }

    // Relations

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieModele::class, 'categorie_modele_id');
    }

    public function modele(): BelongsTo
    {
        return $this->belongsTo(Modele::class, 'modele_id');
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    // Scopes

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre_affichage')->orderByDesc('date_realisation');
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
}
