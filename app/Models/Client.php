<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'telephone',
        'email',
        'adresse',
        'notes',
        'lien_suivi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function mesures(): HasMany
    {
        return $this->hasMany(MesureClient::class);
    }

    public function mesuresGenerales(): HasMany
    {
        return $this->hasMany(MesureClient::class)->whereNull('commande_id');
    }

    public function consentements(): HasMany
    {
        return $this->hasMany(Consentement::class);
    }

    public function rappels(): HasMany
    {
        return $this->hasMany(Rappel::class);
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nom', 'like', "%{$term}%")
              ->orWhere('telephone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    // Helpers

    public function hasConsentement(string $type): bool
    {
        return $this->consentements()
            ->where('type', $type)
            ->where('accepte', true)
            ->exists();
    }
}
