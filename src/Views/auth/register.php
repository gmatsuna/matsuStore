<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>matsuStore - Cadastrar Conta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 p-8 shadow-sm space-y-6">
        <div class="text-center">
            <span class="text-3xl">⚾</span>
            <h1 class="text-2xl font-black text-gray-900 mt-2">Criar sua Conta</h1>
            <p class="text-sm text-gray-500 mt-1">Faça parte da maior loja de baseball japonês</p>
        </div>
        <form action="/cadastro" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400">Nome Completo</label>
                <input type="text" name="name" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400">E-mail</label>
                <input type="email" name="email" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400">Senha</label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm text-sm mt-2">
                Finalizar Cadastro
            </button>
        </form>
        <p class="text-center text-xs text-gray-500">
            Já tem uma conta? <a href="/login" class="text-emerald-600 font-semibold hover:underline">Entrar</a>
        </p>
    </div>
</body>
</html>
