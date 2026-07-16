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

            // 🌟 CORREÇÃO: Enviamos a senha limpa ($password) diretamente.
            // O modelo User::create se encarregará de fazer o hash de forma única e segura.
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password, // Modificado aqui!
                'role' => 'client' // Por padrão, novos cadastros são clientes comuns
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

            if ($user) {
                // 🌟 PROTEÇÃO: Força a conversão para array para blindar contra variações do driver
                $user = (array) $user;

                // Verifica se a senha informada bate com o hash armazenado
                if (password_verify($password, $user['password'])) {
                    
                    // 🛡️ TRAVA DE SEGURANÇA: Verifica se a conta está ativa
                    // Se o campo não existir, consideramos ativo (true) por compatibilidade com usuários antigos
                    $isAtivo = isset($user['isativo']) ? (bool)$user['isativo'] : true;

                    if ($isAtivo === false) {
                        $_SESSION['login_error'] = "Sua conta está desativada. Entre em contato com o administrador.";
                        header('Location: /login');
                        exit;
                    }

                    $_SESSION['user'] = [
                        'id'    => (string)$user['_id'],
                        'name'  => $user['name'],
                        'email' => $user['email'],
                        'role'  => $user['role'] ?? 'client',
                        'isadmin' => (bool)($user['isadmin'] ?? false)
                    ];
                    
                    if ($_SESSION['user']['isadmin'] === true) {
                        header('Location: /admin/dashboard');
                    } else if ($_SESSION['user']['role'] === 'employee') {
                        header('Location: /employee');
                    } else {
                        header('Location: /minha-conta');
                    }
                    exit;
                }
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

    /**
     * Exibe a tela de "Esqueci a Senha" (GET /esqueci-senha)
     */
    public function showForgotPassword()
    {
        require __DIR__ . '/../Views/auth/forgot_password.php';
    }

    /**
     * Processa o envio do formulário de recuperação (POST /esqueci-senha)
     */
    public function sendResetLink()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Por favor, insira um e-mail válido.";
            header('Location: /esqueci-senha');
            exit;
        }

        $database = \App\Config\Database::getDatabase();
        $userCollection = $database->selectCollection('users');
        
        $user = $userCollection->findOne(['email' => $email]);

        if (!$user) {
            $_SESSION['error'] = "Nenhuma conta encontrada com este endereço de e-mail.";
            header('Location: /esqueci-senha');
            exit;
        }

        // Garante leitura segura como objeto
        $user = (object) $user;

        $token = bin2hex(random_bytes(32));
        $expiry = time() + 3600; 

        $userCollection->updateOne(
            ['_id' => $user->_id],
            ['$set' => [
                'reset_token' => $token,
                'reset_expiry' => $expiry
            ]]
        );

        $resetLink = "http://localhost:8080/redefinir-senha?token=" . $token;

        $_SESSION['success'] = "Link gerado com sucesso! (Simulação de E-mail): <br><a href='{$resetLink}' class='underline font-bold text-emerald-900'>Clique aqui para redefinir a senha</a>";

        header('Location: /esqueci-senha');
        exit;
    }

    /**
     * Exibe a tela para digitar a nova senha (GET /redefinir-senha)
     */
    public function showResetPassword()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Token de recuperação ausente ou inválido.";
            header('Location: /login');
            exit;
        }

        $database = \App\Config\Database::getDatabase();
        $userCollection = $database->selectCollection('users');
        $user = $userCollection->findOne(['reset_token' => $token]);

        if (!$user) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Este link de recuperação expirou ou é inválido.";
            header('Location: /esqueci-senha');
            exit;
        }

        $user = (object) $user;

        if (!isset($user->reset_expiry) || time() > $user->reset_expiry) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['error'] = "Este link de recuperação expirou ou é inválido.";
            header('Location: /esqueci-senha');
            exit;
        }

        $userEmail = $user->email;

        require __DIR__ . '/../Views/auth/reset_password.php';
    }

    /**
     * Valida os dados e altera a senha no MongoDB (POST /redefinir-senha)
     */
    public function resetPassword()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token) || empty($password)) {
            $_SESSION['error'] = "Dados inválidos para redefinição.";
            header("Location: /redefinir-senha?token={$token}");
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = "As senhas digitadas não coincidem.";
            header("Location: /redefinir-senha?token={$token}");
            exit;
        }

        $database = \App\Config\Database::getDatabase();
        $userCollection = $database->selectCollection('users');

        $user = $userCollection->findOne(['reset_token' => $token]);

        if (!$user) {
            $_SESSION['error'] = "Este link de recuperação expirou ou é inválido. Solicite um novo.";
            header('Location: /esqueci-senha');
            exit;
        }

        $user = (object) $user;

        if (!isset($user->reset_expiry) || time() > $user->reset_expiry) {
            $_SESSION['error'] = "Este link de recuperação expirou ou é inválido. Solicite um novo.";
            header('Location: /esqueci-senha');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $userCollection->updateOne(
            ['_id' => $user->_id],
            [
                '$set' => ['password' => $hashedPassword],
                '$unset' => ['reset_token' => '', 'reset_expiry' => '']
            ]
        );

        $_SESSION['success'] = "Sua senha foi redefinida com sucesso! Pode fazer o login.";
        header('Location: /login');
        exit;
    }
}