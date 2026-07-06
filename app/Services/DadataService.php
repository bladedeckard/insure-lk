<?php

namespace App\Services;

class DadataService
{
    public function findParty(string $inn): ?array
    {
        $token = config('services.dadata.api_key');
        $secret = config('services.dadata.secret');
        if (!$token) return null;

        try {
            $resp = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Token '.$token,
                'X-Secret' => $secret,
                'Content-Type' => 'application/json',
            ])->post('https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', [
                'query' => $inn
            ])->json();

            return $resp['suggestions'][0] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
