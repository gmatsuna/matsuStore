<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>matsuStore - Entrar na Conta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex min-h-screen items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 p-8 shadow-sm space-y-6">
        
        <div class="text-center">
            <span class="text-3xl">⚾</span>
            <h1 class="text-2xl font-black text-gray-900 mt-2">Acessar sua Conta</h1>
            <p class="text-sm text-gray-500 mt-1">Entre para acompanhar seus pedidos</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-semibold text-center border border-red-100">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-4">
            
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400">E-mail</label>
                <input type="email" name="email" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400">Senha</label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm text-sm mt-2">
                Entrar no Sistema
            </button>
        </form>

        <p class="text-center text-xs text-gray-500">
            Não possui conta? <a href="/cadastro" class="text-emerald-600 font-semibold hover:underline">Cadastre-se grátis</a>
        </p>

    </div>

</body>
</html>
