<?php

namespace App\Config;

use MongoDB\Client;
use MongoDB\Database as MongoDatabase;

class Database
{
    private static ?Client $client = null;

    public static function getClient(): Client
    {
        if (self::$client === null) {
            $env = self::loadEnv();
            self::$client = new Client($env['MONGODB_URI']);
        }

        return self::$client;
    }

    public static function getDatabase(): MongoDatabase
    {
        $env = self::loadEnv();
        return self::getClient()->selectDatabase($env['MONGODB_DATABASE']);
    }

    private static function loadEnv(): array
    {
        $lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env = [];
        foreach ($lines as $line) {
            if (strpos($line, '=') === false) continue;
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
        return $env;
    }
}
