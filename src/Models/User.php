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
    /**
     * Salva um novo usuário no banco de dados de forma padronizada
     */
    public static function create(array $data): bool
    {
        try {
            // Criptografa a senha usando o algoritmo seguro BCRYPT antes de salvar
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            
            
            // 1. Garante que campos ausentes de controle administrativo possuam valores padrão
            if (!isset($data['isadmin'])) {
                $data['isadmin'] = false;
                }
                if (!isset($data['isativo'])) {
                    $data['isativo'] = true;
                    }
                    
            // 2. Garante que o campo de data seja salvo usando a classe nativa do MongoDB
            $data['created_at'] = new \MongoDB\BSON\UTCDateTime();

            // Opcional: Remove o campo antigo caso ele venha no array por algum formulário
            unset($data['member_since']);
            
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