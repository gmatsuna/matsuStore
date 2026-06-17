<?php

namespace App\Models;

use App\Config\Database;
use MongoDB\Collection;

class Product
{
    private static function collection(): Collection
    {
        return Database::getDatabase()->selectCollection('products');
    }

    public static function all(): array
    {
        $cursor = self::collection()->find();
        return $cursor->toArray();
    }
}
