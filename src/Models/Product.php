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

    public static function all(array $filter = []): array
    {
        try {
            // Passamos o $filter direto para o find() do MongoDB
            // E mantemos o typeMap que resolveu nosso problema de tipagem!
            return self::collection()->find($filter, [
                'typeMap' => [
                    'root' => 'stdClass',
                    'document' => 'stdClass',
                    'array' => 'array'
                ]
            ])->toArray();
        } catch (\Exception $e) {
            return [];
        }
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
