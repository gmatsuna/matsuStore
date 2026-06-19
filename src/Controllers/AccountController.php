<?php

namespace App\Controllers;

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
            header('Location: /login');
            exit;
        }

        // Se chegou aqui, está logado! Pega os dados reais guardados na sessão
        $user = $_SESSION['user'];

        // Histórico de pedidos (pode começar vazio para novos usuários)
        $orders = []; 

        // Carrega a tela visual da conta
        require_once __DIR__ . '/../Views/account.php';
    }
}
