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

    public static function find(string $id): ?\stdClass
    {
        try {
            $objectId = new \MongoDB\BSON\ObjectId($id);
            
            // Passamos o typeMap para converter o documento BSON diretamente em stdClass do PHP
            return self::collection()->findOne(
                ['_id' => $objectId],
                [
                    'typeMap' => [
                        'root' => 'stdClass',
                        'document' => 'stdClass',
                        'array' => 'array'
                    ]
                ]
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}
