<?php

// 1. Ativa a exibição de erros na tela para nos ajudar no desenvolvimento local
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Carrega o Autoloader do Composer (essencial para o MVC e para o MongoDB)
require_once __DIR__ . '/../vendor/autoload.php';

// 3. Captura a URL (URI) que o usuário digitou
// O 'REQUEST_URI' traz algo como '/produtos' ou '/'
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 4. Nossa tabela de rotas (Mapeamento de URL -> [Controller, Método])
$routes = [
    '/' => [\App\Controllers\HomeController::class, 'index'],
    // No futuro, adicionaremos outras aqui:
    // '/produto' => [\App\Controllers\ProductController::class, 'show'],
];

// 5. Verifica se a URL digitada existe no nosso mapa de rotas
if (array_key_exists($uri, $routes)) {
    // Se existir, pegamos o nome da Classe e o Método
    [$controllerClass, $method] = $routes[$uri];

    // Instanciamos o controlador dinamicamente (Ex: new HomeController())
    $controller = new $controllerClass();

    // Chamamos o método dinamicamente (Ex: $controller->index())
    $controller->$method();
} else {
    // Se a rota não existir, retornamos um erro 404 clássico
    http_response_code(404);
    echo "<h1>Erro 404 - Página não encontrada</h1>";
    echo "A rota <strong>" . htmlspecialchars($uri) . "</strong> não foi definida no sistema.";
}