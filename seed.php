<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    echo "⚾ Conectando ao MongoDB para atualizar o estoque da matsuStore...\n";
    $collection = Database::getDatabase()->selectCollection('products');

    // 1. Limpa os produtos antigos para não duplicar ou misturar temas
    $collection->deleteMany([]);
    echo "🧹 Estoque antigo limpo com sucesso.\n";

    // 2. Novos produtos inspirados na NPB (Liga Japonesa) com estoque definido
    $products = [
        [
            'name' => 'Taco de Madeira Mizuno Pro - Yomiuri Giants Edition',
            'description' => 'Taco profissional de Maple confeccionado no Japão. Modelo oficial utilizado pelos rebatedores do tradicional Yomiuri Giants de Tóquio.',
            'price' => 1250.00,
            'category' => 'Tacos',
            'team' => 'Yomiuri Giants',
            'image' => 'taco-mizuno.webp',
            'stock' => 5 // Estoque baixo para testar o limite facilmente
        ],
        [
            'name' => 'Luva de Infield SSK Proedge - Hanshin Tigers',
            'description' => 'Luva premium de couro japonês legítimo, tamanho 11.5 polegadas. Edição limitada nas cores preta e amarela do atual campeão Hanshin Tigers.',
            'price' => 2400.00,
            'category' => 'Luvas',
            'team' => 'Hanshin Tigers',
            'image' => 'luva-ssk.jpeg',
            'stock' => 3 // Bem limitado!
        ],
        [
            'name' => 'Boné Oficial Majestic - Fukuoka SoftBank Hawks',
            'description' => 'Boné de jogo ajustável com o icônico Sh-logo bordado em alta definição. Tecnologia de absorção de suor ideal para treinos e torcida.',
            'price' => 289.90,
            'category' => 'Vestuário',
            'team' => 'Fukuoka SoftBank Hawks',
            'image' => 'bone-hawks.png',
            'stock' => 15
        ],
        [
            'name' => 'Jersey de Jogo Home - Yokohama DeNA BayStars',
            'description' => 'Camisa oficial listrada azul e branca com tecnologia de alta performance. Sinta o clima do Yokohama Stadium com o manto dos BayStars.',
            'price' => 699.00,
            'category' => 'Vestuário',
            'team' => 'Yokohama DeNA BayStars',
            'image' => 'jersey-baystars.png',
            'stock' => 8
        ],
        [
            'name' => 'Caixa de Bolas Oficiais de Jogo Mizuno 200 - NPB',
            'description' => 'Pack com 12 unidades das bolas oficiais utilizadas na liga japonesa. Miolo de cortiça e borracha envolto por lã premium e costuras vermelhas perfeitas.',
            'price' => 450.00,
            'category' => 'Bolas',
            'team' => 'NPB',
            'image' => 'bolas-npb.png',
            'stock' => 20
        ],
        [
            'name' => 'Capacete de Rebatida ZETT - Tokyo Yakult Swallows',
            'description' => 'Proteção profissional com dupla orelha e acabamento fosco em azul marinho. Logotipo clássico do Yakult Swallows na parte frontal.',
            'price' => 520.00,
            'category' => 'Acessórios',
            'team' => 'Tokyo Yakult Swallows',
            'image' => 'capacete-zett.webp',
            'stock' => 4
        ]
    ];

    // 3. Insere os novos dados no banco
    $result = $collection->insertMany($products);
    echo "🚀 Sucesso! " . $result->getInsertedCount() . " itens da NPB foram cadastrados no banco com controle de estoque.\n";

} catch (\Exception $e) {
    echo "❌ Erro ao rodar o seed: " . $e->getMessage() . "\n";
}