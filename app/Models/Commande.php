<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'reference',
        'client_id',
        'modele_id',
        'type',
        'statut',
        'prix_propose',
        'prix_final',
        'reduction_client_fournit',
        'date_commande',
        'date_livraison_prevue',
        'date_livraison_reelle',
        'notes_internes',
        'notes_client',
        'lien_suivi',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrderType::class,
            'statut' => OrderStatus::class,
            'prix_propose' => 'decimal:2',
            'prix_final' => 'decimal:2',
            'reduction_client_fournit' => 'decimal:2',
            'date_commande' => 'date',
            'date_livraison_prevue' => 'date',
            'date_livraison_reelle' => 'date',
        ];
    }

    // Relations

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function modele(): BelongsTo
    {
        return $this->belongsTo(Modele::class, 'modele_id');
    }

    public function accessoires(): BelongsToMany
    {
        return $this->belongsToMany(Accessoire::class, 'commande_accessoires', 'commande_id', 'accessoire_id')
                    ->withPivot(['quantite', 'prix_unitaire_snapshot', 'fourni_par_client'])
                    ->withTimestamps();
    }

    public function mesures(): HasMany
    {
        return $this->hasMany(MesureClient::class, 'commande_id');
    }

    public function rappels(): HasMany
    {
        return $this->hasMany(Rappel::class, 'commande_id');
    }

    public function realisation(): HasOne
    {
        return $this->hasOne(RealisationPortfolio::class, 'commande_id');
    }

    // Scopes

    public function scopeEnCours(Builder $query): Builder
    {
        return $query->whereNotIn('statut', [
            OrderStatus::Livree->value,
            OrderStatus::Annulee->value,
        ]);
    }

    public function scopeTerminees(Builder $query): Builder
    {
        return $query->where('statut', OrderStatus::Livree->value);
    }

    public function scopeAnnulees(Builder $query): Builder
    {
        return $query->where('statut', OrderStatus::Annulee->value);
    }

    public function scopePrecommandes(Builder $query): Builder
    {
        return $query->where('statut', OrderStatus::Precommande->value);
    }

    public function scopePourClient(Builder $query, int $clientId): Builder
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeEnRetard(Builder $query): Builder
    {
        return $query->enCours()
                     ->where('date_livraison_prevue', '<', now()->toDateString());
    }

    public function scopeALivrerCetteSemaine(Builder $query): Builder
    {
        return $query->enCours()
                     ->whereBetween('date_livraison_prevue', [
                         now()->toDateString(),
                         now()->addDays(7)->toDateString(),
                     ]);
    }

    public function scopeByStatut(Builder $query, OrderStatus $statut): Builder
    {
        return $query->where('statut', $statut->value);
    }

    // Helpers

    public function isEnRetard(): bool
    {
        return !$this->statut->isTerminal()
            && $this->date_livraison_prevue
            && $this->date_livraison_prevue->isPast();
    }

    public function joursRetard(): int
    {
        if (!$this->isEnRetard()) {
            return 0;
        }

        return (int) $this->date_livraison_prevue->diffInDays(now());
    }
}
