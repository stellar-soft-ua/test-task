<?php

namespace App\Enums;

enum VerificationResult: int
{
    case Verified = 0;
    case InvalidRecipient = 1;
    case InvalidIssuer = 2;
    case InvalidSignature = 3;

    public static function toArray(): array
    {
        return array_column(VerificationResult::cases(), 'name');
    }
}
