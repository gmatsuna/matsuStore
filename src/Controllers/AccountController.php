<?php

namespace App\Controllers;

use App\Config\Database;

class AccountController
{
    public function index()
    {
        // Garante que a sessão está ativa para podermos checar o "crachá"
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // SE NÃO EXISTIR o usuário na sessão, redireciona imediatamente para o login
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Você precisa estar logado para acessar esta área.";
            header('Location: /login');
            exit;
        }

        // Se chegou aqui, está logado! Pega os dados reais guardados na sessão
        $user = $_SESSION['user']['id'];

        $db = Database::getDatabase();

        // Conecta ao banco de dados e procura os pedidos do utilizador ativos ordenados pelos mais recentes
        // Substitua o bloco do find por este:
        $cursor = $db->orders->find(
            ['user_id' => $user],
            ['sort' => ['created_at' => -1]]
        );

        $orders = iterator_to_array($cursor);

        // Carrega a tela visual da conta
        require_once __DIR__ . '/../Views/account.php';
    }
}
