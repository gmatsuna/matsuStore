<?php
// Garante que o cabeçalho consiga ler o "crachá" da sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica se o usuário está logado
$isLogged = isset($_SESSION['user']);
$loggedUser = $isLogged ? $_SESSION['user'] : null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>matsuStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="hover:opacity-85 transition inline-block">
                <h1 class="text-2xl sm:text-3xl font-black tracking-tighter text-gray-900 uppercase">
                    matsu<span class="text-emerald-600 font-extrabold text-opacity-90">Store</span>
                </h1>
            </a>
            
            <form action="/" method="GET" class="flex-1 max-w-lg mx-4 hidden sm:block">
                <div class="relative text-gray-400 focus-within:text-emerald-600">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        ⚾
                    </div>
                    <input 
                        type="text" 
                        name="busca" 
                        value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>"
                        placeholder="Procurar tacos, luvas, times da NPB..." 
                        class="block w-full rounded-xl border-0 py-2 pl-10 pr-3 text-gray-900 bg-gray-100 placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-emerald-600 transition text-sm"
                    >
                </div>
            </form>

            <div class="flex items-center space-x-4">
                <div class="flex items-center gap-4">
                    <?php if ($isLogged): ?>
                        <a href="/minha-conta" class="text-sm font-semibold text-gray-700 hover:text-emerald-600 transition flex items-center gap-1">
                            <span>👤</span> Olá, <?= htmlspecialchars($loggedUser['name']) ?>
                        </a>
                        <a href="/logout" class="text-sm font-bold text-red-600 hover:text-red-700 transition flex items-center gap-1 border-l border-gray-200 pl-4">
                            <span>🚪</span> Sair
                        </a>
                    <?php else: ?>
                        <a href="/login" class="text-sm font-semibold text-gray-700 hover:text-emerald-600 transition">
                            Entrar
                        </a>
                        <a href="/cadastro" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl text-sm transition shadow-sm">
                            Cadastrar
                        </a>
                    <?php endif; ?>
                </div>
                <!-- Subsitua a tag <a> do carrinho por esta versão corrigida: -->
                <a href="/carrinho" class="relative hover:opacity-85 transition inline-flex items-center ml-4">
                    <span class="text-xl">🛒</span>
                    <?php 
                        $totalItens = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $totalItens += $item['quantity'];
                            }
                        }
                    ?>
                    <span class="absolute -top-1.5 -right-2 bg-emerald-600 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black dynamic-cart-badge">
                        <?= $totalItens ?>
                    </span>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-full mx-auto w-full px-4 sm:px-6 lg:px-12 flex flex-1 py-6 gap-6">