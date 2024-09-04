<?php

namespace App\Models;

use App\Enums\FileType;
use App\Enums\VerificationResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_type',
        'verification_result',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_type' => FileType::class,
            'verification_result' => VerificationResult::class,
        ];
    }
}
