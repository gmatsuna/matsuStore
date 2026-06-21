<?php

namespace App\Controllers;

use App\Models\Product;

class CartController
{
    public function __construct()
    {
        // Garante que a sessão (onde ficará salvo o carrinho) esteja ativa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Se o carrinho ainda não existir na sessão, inicializa ele como um array vazio
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Exibe a página do carrinho de compras
     */
    public function index()
    {
        echo "<h1>Tela do Carrinho (Em breve)</h1><p>Aqui listaremos os produtos salvos na sessão.</p>";
    }

    /**
     * Processa a adição de um produto ao carrinho (POST)
     */
    public function add()
    {
        echo "<h1>Processando adição ao carrinho...</h1>";
    }

    /**
     * Remove um produto do carrinho
     */
    public function remove()
    {
        echo "<h1>Removendo produto do carrinho...</h1>";
    }
}