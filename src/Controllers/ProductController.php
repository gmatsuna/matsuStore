<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController
{
    public function show(): void
    {
        // 1. Pega o ID da URL. Se não existir, deixa em branco
        $id = $_GET['id'] ?? '';

        // 2. Se não houver ID, redireciona para a home
        if (empty($id)) {
            header('Location: /');
            exit;
        }

        // 3. Busca o produto no MongoDB
        $product = Product::find($id);

        // 4. Se o produto não existir no banco, joga erro 404
        if (!$product) {
            http_response_code(404);
            echo "<h1>Produto não encontrado!</h1>";
            exit;
        }

        // 5. Se deu tudo certo, carrega a View de detalhes
        require __DIR__ . '/../Views/product_detail.php';
    }
}