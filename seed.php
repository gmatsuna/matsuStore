<?php
require __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

$db = Database::getDatabase();
$collection = $db->selectCollection('products');

$collection->insertMany([
    [
        'name' => 'Camiseta Básica',
        'price' => 49.90,
        'description' => 'Camiseta 100% algodão, confortável para o dia a dia.',
        'stock' => 25,
    ],
    [
        'name' => 'Tênis Esportivo',
        'price' => 199.90,
        'description' => 'Ideal para corrida e atividades físicas.',
        'stock' => 12,
    ],
    [
        'name' => 'Mochila Casual',
        'price' => 89.90,
        'description' => 'Mochila resistente com compartimento para notebook.',
        'stock' => 8,
    ],
]);

echo "✅ Produtos inseridos com sucesso!\n";
