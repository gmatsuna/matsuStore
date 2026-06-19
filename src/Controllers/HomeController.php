<?php

namespace App\Controllers;

use App\Models\Product;

class HomeController
{
    public function index()
    {
        $filter = [];
        
        // 1. Filtro por Categoria (Menu Lateral)
        if (!empty($_GET['categoria'])) {
            $filter['category'] = htmlspecialchars($_GET['categoria']);
        }

        // 2. Filtro por Termo de Busca (Header)
        if (!empty($_GET['busca'])) {
            $termo = htmlspecialchars($_GET['busca']);
            
            // Procura o termo dentro do campo 'name'
            // A opção 'i' torna a busca case-insensitive (ignora maiúsculas/minúsculas)
            $filter['name'] = new \MongoDB\BSON\Regex($termo, 'i');
        }

        // Busca os produtos aplicando os filtros acumulados
        $products = Product::all($filter);

        // Verifica se a requisição veio via JavaScript (AJAX)
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            // Devolve APENAS o HTML dos cards para o JavaScript injetar na div "grid-produtos"
            require_once __DIR__ . '/../Views/partials/product_grid.php';
        } else {
            // Acesso convencional: Carrega a página inteira com Header, Banner, Sidebar, etc.
            require_once __DIR__ . '/../Views/home.php';
        }
        // ==============================================
    }
}