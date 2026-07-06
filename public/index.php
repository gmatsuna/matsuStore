<?php

header('Content-Type: text/html; charset=utf-8');

// 1. Ativa a exibição de erros na tela para nos ajudar no desenvolvimento local
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Carrega o Autoloader do Composer (essencial para o MVC e para o MongoDB)
require_once __DIR__ . '/../vendor/autoload.php';

// 3. Captura a URL (URI) que o usuário digitou
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 4. Captura o método HTTP usado (Se é GET ou POST)
$methodHttp = $_SERVER['REQUEST_METHOD'];

// 5. Nossa tabela de rotas atualizada
$routes = [
    'GET' => [
        '/'                 => [\App\Controllers\HomeController::class, 'index'],
        '/produto'          => [\App\Controllers\ProductController::class, 'show'],
        '/minha-conta'      => [\App\Controllers\AccountController::class, 'index'],
        '/logout'           => [\App\Controllers\AuthController::class, 'logout'],
        '/carrinho'         => [\App\Controllers\CartController::class, 'index'],
        '/cadastro'         => [\App\Controllers\AuthController::class, 'showRegister'],
        '/login'            => [\App\Controllers\AuthController::class, 'showLogin'],
        '/checkout'         => [\App\Controllers\CheckoutController::class, 'index'],
    ],
    'POST' => [
        '/cadastro'           => [\App\Controllers\AuthController::class, 'register'],
        '/login'              => [\App\Controllers\AuthController::class, 'login'],
        '/carrinho/adicionar' => [\App\Controllers\CartController::class, 'add'],
        '/carrinho/remover'   => [\App\Controllers\CartController::class, 'remove'],
        '/carrinho/atualizar' => [\App\Controllers\CartController::class, 'updateQuantity'],
        '/pedido/finalizar'   => [\App\Controllers\CheckoutController::class, 'finish'],
    ]
];

if (isset($routes[$methodHttp]) && array_key_exists($uri, $routes[$methodHttp])) {
    [$controllerClass, $method] = $routes[$methodHttp][$uri];

    // Instancia o controlador dinamicamente
    $controller = new $controllerClass();

    // Chama o método dinamicamente
    $controller->$method();

    exit;

} else {
    http_response_code(404);
    echo "<h1>Erro 404 - Página não encontrada</h1>";
    echo "A rota <strong>" . htmlspecialchars($uri) . "</strong> não foi definida para o método <strong>" . $methodHttp . "</strong>.";
}

