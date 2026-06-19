<?php

namespace App\Models;

use App\Config\Database;

class User
{
    /**
     * Conecta diretamente à coleção 'users' dentro do MongoDB
     */
    private static function collection()
    {
        return Database::getDatabase()->selectCollection('users');
    }

    /**
     * Salva um novo usuário no banco de dados
     */
    public static function create(array $data): bool
    {
        try {
            // Criptografa a senha usando o algoritmo seguro BCRYPT antes de salvar
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            
            // Guarda o mês e ano em que a conta foi criada
            $data['member_since'] = date('M, Y');
            
            // Insere o documento no MongoDB
            self::collection()->insertOne($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Procura um usuário no banco pelo e-mail (essencial para o futuro Login)
     */
    public static function findByEmail(string $email): ?\stdClass
    {
        try {
            return self::collection()->findOne(
                ['email' => $email],
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