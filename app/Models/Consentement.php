<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consentement extends Model
{
    protected $table = 'consentements';

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'type',
        'accepte',
        'date_consentement',
        'ip_address',
        'moyen',
    ];

    protected function casts(): array
    {
        return [
            'accepte' => 'boolean',
            'date_consentement' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    // Relations

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
