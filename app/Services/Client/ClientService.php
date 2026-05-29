<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Models\Consentement;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {}

    public function creerClient(array $data): Client
    {
        $data['lien_suivi'] = Str::random(64);
        return $this->clientRepository->create($data);
    }

    public function updateClient(Client $client, array $data): Client
    {
        $this->clientRepository->update($client, $data);
        return $client->fresh();
    }

    public function getClientDetails(int $id): ?Client
    {
        return $this->clientRepository->find($id);
    }

    public function getClientsActifs(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->clientRepository->search($search, $perPage);
    }

    public function recordConsent(Client $client, string $type, bool $accepte, string $moyen, ?string $ip = null): Consentement
    {
        return Consentement::create([
            'client_id' => $client->id,
            'type' => $type,
            'accepte' => $accepte,
            'date_consentement' => now(),
            'ip_address' => $ip,
            'moyen' => $moyen,
        ]);
    }
}
