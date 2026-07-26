<?php

namespace App\Services;

class DadataService
{
    private function getHeaders(): array
    {
        $token = config('services.dadata.api_key');
        $secret = config('services.dadata.secret');
        return [
            'Authorization' => 'Token ' . $token,
            'X-Secret' => $secret,
            'Content-Type' => 'application/json',
        ];
    }

    private function isConfigured(): bool
    {
        return !empty(config('services.dadata.api_key'));
    }

    public function findParty(string $inn): ?array
    {
        if (!$this->isConfigured()) return null;

        try {
            $resp = \Illuminate\Support\Facades\Http::withHeaders($this->getHeaders())
                ->post('https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', [
                    'query' => $inn,
                ])->json();

            return $resp['suggestions'][0] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function suggestAddress(string $query): array
    {
        if (!$this->isConfigured() || empty($query)) return [];

        try {
            $resp = \Illuminate\Support\Facades\Http::withHeaders($this->getHeaders())
                ->timeout(5)
                ->post('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', [
                    'query' => $query,
                    'count' => 5,
                    'locations' => [['country' => 'Россия']],
                    'restrict_value' => false,
                ])->json();

            return $resp['suggestions'] ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getCadastreNumber(string $address): ?string
    {
        if (!$this->isConfigured() || empty($address)) return null;

        try {
            $suggestions = $this->suggestAddress($address);
            if (empty($suggestions)) return null;

            $fiasId = $suggestions[0]['data']['fias_id'] ?? null;
            if (!$fiasId) return null;

            return $suggestions[0]['data']['house_fias_id'] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
