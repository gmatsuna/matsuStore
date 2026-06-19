<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function __construct()
    {
        // Garante que a sessão (crachá digital) esteja ativa no PHP
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showRegister()
    {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function register()
    {
        $name = htmlspecialchars($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if ($name && $email && $password) {
            if (User::findByEmail($email)) {
                echo "Erro: Este e-mail já está cadastrado em nossa loja.";
                return;
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);

            header('Location: /login');
            exit;
        }

        echo "Erro: Dados inválidos enviados no formulário.";
    }

    /**
     * Exibe a tela de login
     */
    public function showLogin()
    {
        // Pega alguma mensagem de erro temporária da sessão (se houver)
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']); // Limpa para não ficar repetindo o erro

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Processa a validação do Login
     */
    public function login()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if ($email && $password) {
            // Busca o usuário no MongoDB pelo e-mail informado
            $user = User::findByEmail($email);

            // Verifica se o usuário existe e se a senha descriptografada bate com o hash
            if ($user && password_verify($password, $user->password)) {
                
                // Cria o "crachá digital" salvando os dados na Sessão
                $_SESSION['user'] = [
                    'id' => (string)$user->_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'member_since' => $user->member_since ?? date('M, Y')
                ];

                // Login funcionou! Redireciona para o painel de gerenciamento da conta
                header('Location: /minha-conta');
                exit;
            }
        }

        // Se errou o e-mail ou a senha, guarda o erro na sessão e recarrega a página
        $_SESSION['login_error'] = 'E-mail ou senha inválidos.';
        header('Location: /login');
        exit;
    }

    public function logout()
    {
        // Limpa todas as variáveis salvas na sessão
        $_SESSION = [];

        // Destrói o arquivo físico da sessão no servidor
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        // Manda o usuário limpo de volta para a Home
        header('Location: /');
        exit;
    }
}
