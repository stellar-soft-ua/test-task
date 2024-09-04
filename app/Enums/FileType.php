<?php

namespace App\Enums;

enum FileType: int
{
    case Json = 0;

    public static function toArray(): array
    {
        return array_column(FileType::cases(), 'name');
    }
}
