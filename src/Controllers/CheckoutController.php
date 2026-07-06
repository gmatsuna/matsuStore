<?php

namespace App\Controllers;

// Importamos a classe do banco com o namespace correto
use App\Config\Database;
use MongoDB\BSON\ObjectId;

class CheckoutController
{
    public function index()
    {
        // 1. Garante que a sessão esteja ativa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Se o usuário não estiver logado, redireciona para o login
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // 3. Captura os itens que já estão na sessão (igualzinho ao seu CartController)
        $cartItems = $_SESSION['cart'] ?? [];

        // 4. Se o carrinho estiver vazio, manda de volta para a página do carrinho
        if (empty($cartItems)) {
            header('Location: /carrinho');
            exit;
        }

        // 5. Calcula o subtotal para passar para a View
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // 6. Carrega a view do frontend
        require_once __DIR__ . '/../Views/checkout.php';
    }

    public function finish()
    {
        // 1. Garante que a sessão esteja ativa e o usuário logado
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // 2. Verifica se o carrinho existe e tem itens
        $cartItems = $_SESSION['cart'] ?? [];
        if (empty($cartItems)) {
            header('Location: /carrinho');
            exit;
        }

        // 3. Captura os dados de entrega e pagamento enviados pelo formulário
        $cep = trim($_POST['cep'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $complemento = trim($_POST['complemento'] ?? '');
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = trim($_POST['estado'] ?? '');
        $metodoPagamento = $_POST['metodo_pagamento'] ?? '';

        // Validação simples (garantir que os campos obrigatórios foram preenchidos)
        if (empty($cep) || empty($endereco) || empty($numero) || empty($bairro) || empty($cidade) || empty($estado) || empty($metodoPagamento)) {
            $_SESSION['error'] = "Por favor, preencha todos os campos obrigatórios do endereço e pagamento.";
            header('Location: /checkout');
            exit;
        }

        // 4. Calcula o total geral do pedido
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            // 5. Conecta ao MongoDB usando a sua classe de Config
            $db = Database::getDatabase();

            // 6. Monta a estrutura do documento do Pedido
            $orderDocument = [
                'user_id' => $_SESSION['user']['id'], // ID do usuário logado
                'items' => array_values($cartItems), // Mantém a lista de itens comprados
                'total' => (float)$total,
                'shipping_address' => [
                    'cep' => $cep,
                    'endereco' => $endereco,
                    'numero' => $numero,
                    'complemento' => $complemento,
                    'bairro' => $bairro,
                    'cidade' => $cidade,
                    'estado' => strtoupper($estado),
                ],
                'payment_method' => $metodoPagamento,
                'status' => 'pendente', // Todo pedido novo nasce pendente
                'created_at' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
            ];

            // 7. Insere na coleção "orders"
            $db->orders->insertOne($orderDocument);

            // 8. Limpa o carrinho da sessão já que o pedido foi fechado com sucesso
            unset($_SESSION['cart']);

            // Redireciona para uma página de sucesso (ou para a página "Minha Conta" onde listaremos os pedidos)
            header('Location: /minha-conta?sucesso=pedido_realizado');
            exit;

        } catch (\Exception $e) {
            // Se der erro no banco, exibe a mensagem para debug local
            echo "Erro ao salvar o pedido: " . $e->getMessage();
            exit;
        }
    }
}