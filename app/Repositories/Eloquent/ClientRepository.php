<?php

namespace App\Repositories\Eloquent;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{
    public function find(int $id): ?Client
    {
        return Client::find($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): bool
    {
        return $client->update($data);
    }

    public function search(?string $term, int $perPage = 15): LengthAwarePaginator
    {
        $query = Client::active();

        if ($term) {
            $query->search($term);
        }

        return $query->orderBy('nom')->paginate($perPage);
    }

    public function findByTelephone(string $telephone): ?Client
    {
        return Client::where('telephone', $telephone)->first();
    }

    public function findByLienSuivi(string $lienSuivi): ?Client
    {
        return Client::where('lien_suivi', $lienSuivi)->first();
    }
}
