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

        // 🛡️ TRAVA DE SEGURANÇA: Se não estiver logado, redireciona para a página de login
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Você precisa estar logado para acessar ou gerenciar seu carrinho de compras.";
            header('Location: /login');
            exit;
        }
        
        // Pega os itens salvos no carrinho (ou um array vazio se não houver nada)
        $cartItems = $_SESSION['cart'] ?? [];

        // Calcula o subtotal geral do carrinho para passar para a tela
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Carrega a tela visual do carrinho e injeta as variáveis acima
        require_once __DIR__ . '/../Views/cart.php';
    }

    /**
     * Processa a adição de um produto ao carrinho (POST)
     */
    /**
     * Adiciona um produto ao carrinho (POST /carrinho/adicionar)
     */
    public function add()
    {
        $productId = $_POST['product_id'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (empty($productId) || $quantity < 1) {
            header('Location: /');
            exit;
        }

        // 1. Busca o produto diretamente no MongoDB para checar o estoque real
        // Substitua pela sua instância/chamada real do Model ou Conexão do MongoDB
        $productModel = new \App\Models\Product(); 
        $product = $productModel->find($productId);

        if (!$product) {
            header('Location: /');
            exit;
        }

        // Pega o estoque atual (garantindo que seja tratado como número)
        $stockAvailable = isset($product->stock) ? (int)$product->stock : 0;

        // 2. Calcula quanto o usuário já tem desse item no carrinho atual da sessão
        $currentInCart = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId]['quantity'] : 0;
        $totalRequested = $currentInCart + $quantity;

        // 3. Validação crucial de Estoque
        if ($totalRequested > $stockAvailable) {
            // Se estourar o estoque, joga de volta para a página do produto com uma mensagem de erro
            $_SESSION['error'] = "Desculpe, estoque insuficiente! Temos apenas {$stockAvailable} unidade(s) disponível(is).";
            header("Location: /produto?id={$productId}");
            exit;
        }

        // Se passar no teste, adiciona ou incrementa na sessão normalmente
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id'       => (string)$product->_id,
                'name'     => $product->name,
                'price'    => (float)$product->price,
                'image'    => $product->image ?? '',
                'category' => $product->category ?? '',
                'quantity' => $quantity
            ];
        }

        header('Location: /carrinho');
        exit;
    }

    /**
     * Remove um produto do carrinho
     */
    /**
     * Remove um produto do carrinho via POST
     */
    public function remove()
    {
        // 1. Captura o ID do produto vindo de forma segura via POST
        $productId = $_POST['product_id'] ?? '';

        // 2. Se o ID não for enviado, apenas joga o usuário de volta para o carrinho
        if (empty($productId)) {
            header('Location: /carrinho');
            exit;
        }

        // 3. Verifica se esse produto realmente existe no carrinho atual da sessão
        if (isset($_SESSION['cart'][$productId])) {
            // Remove a gaveta correspondente àquele produto usando unset
            unset($_SESSION['cart'][$productId]);
        }

        // 4. Redireciona de volta para a tela do carrinho para ver o resultado atualizado
        header('Location: /carrinho');
        exit;
    }

    /**
     * Atualiza a quantidade via requisição AJAX (POST /carrinho/atualizar)
     */
    public function updateQuantity()
    {
        header('Content-Type: application/json');

        $productId = $_POST['product_id'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (empty($productId) || $quantity < 1) {
            echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
            exit;
        }

        // 1. Busca o produto no banco de dados para validar o teto do estoque
        $productModel = new \App\Models\Product();
        $product = $productModel->find($productId);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Produto não encontrado no sistema.']);
            exit;
        }

        $stockAvailable = isset($product->stock) ? (int)$product->stock : 0;

        // 2. Validação de Estoque
        if ($quantity > $stockAvailable) {
            echo json_encode([
                'success' => false, 
                'message' => "Estoque máximo atingido! Apenas {$stockAvailable} unidades em estoque."
            ]);
            exit;
        }

        // Se houver estoque, atualiza a sessão
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
            $itemSubtotal = $_SESSION['cart'][$productId]['price'] * $quantity;
        } else {
            echo json_encode(['success' => false, 'message' => 'Produto não está no seu carrinho.']);
            exit;
        }

        // Recalcula totais gerais para o retorno do JSON
        $cartTotal = 0;
        $totalItemsCount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
            $totalItemsCount += $item['quantity'];
        }

        echo json_encode([
            'success'      => true,
            'itemSubtotal' => 'R$ ' . number_format($itemSubtotal, 2, ',', '.'),
            'cartTotal'    => 'R$ ' . number_format($cartTotal, 2, ',', '.'),
            'totalItems'   => $totalItemsCount
        ]);
        exit;
    }
}