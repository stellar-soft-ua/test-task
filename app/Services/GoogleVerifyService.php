<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleVerifyService implements \App\Interfaces\VerifyService
{
    public function lookupDns(string $domain): array
    {
        try {

            $result = Http::get("https://dns.google/resolve?name={$domain}&type=TXT")->json();

            return $result['Answer'] ?? [];

        } catch (\Exception $e) {
            return [];
        }
    }
}
