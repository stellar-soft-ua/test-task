<?php

namespace App\Interfaces;

interface VerifyService
{
    public function lookupDns(string $domain): array;
}
