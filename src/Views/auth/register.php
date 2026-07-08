<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>matsuStore - Cadastrar Conta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex min-h-screen flex-col items-center justify-center p-4 sm:p-6 md:p-8 space-y-6">

    <!-- Container Central de Controle de Largura (Unificado) -->
    <div class="w-full max-w-2xl flex flex-col space-y-4">
        
        <!-- Botão de Voltar para a Home -->
        <div class="flex justify-start">
            <a href="/" class="group inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-400 hover:text-emerald-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar para a Home
            </a>
        </div>

        <!-- Banner Padrão da Loja (Texto Ultra Compacto e Marca d'água Isolada na Base) -->
        <section class="bg-gradient-to-r from-emerald-700 to-teal-900 rounded-2xl p-8 text-white shadow-sm relative overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        
            <div class="max-w-sm relative z-10 space-y-3 md:pr-4">
                <span class="text-xs font-bold uppercase tracking-widest bg-emerald-500 text-white px-2.5 py-1 rounded-full bg-opacity-30 inline-block">Pure Baseball</span>
                <h2 class="text-3xl font-black tracking-tight sm:text-4xl">Viva a Paixão do Baseball Japonês</h2>
                <p class="text-emerald-100 text-sm leading-relaxed max-w-xs">
                    Equipamentos profissionais das grandes marcas da NPB. Garanta mantos oficiais, tacos de Maple e luvas exclusivas com frete grátis para todo o Brasil.
                </p>
            </div>

            <div class="absolute right-4 bottom-50 opacity-10 pointer-events-none select-none hidden sm:block">
                <span class="text-7xl lg:text-6xl font-black tracking-tighter">MatsuStore</span>
            </div>

        </section>

        <!-- Card de Cadastro Expandido (Acompanhando a largura do Banner) -->
        <div class="w-full bg-white rounded-2xl border border-gray-200 p-8 md:p-10 shadow-sm space-y-6">
            
            <div class="text-center">
                <span class="text-3xl">⚾</span>
                <h1 class="text-2xl font-black text-gray-900 mt-2">Criar sua Conta</h1>
                <p class="text-sm text-gray-500 mt-1">Faça parte da maior loja de baseball japonês</p>
            </div>

            <form action="/cadastro" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400">Nome Completo</label>
                    <input type="text" name="name" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400">E-mail</label>
                    <input type="email" name="email" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400">Senha</label>
                    <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl mt-1 text-sm p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-sm text-sm mt-2">
                    Finalizar Cadastro
                </button>
            </form>

            <p class="text-center text-xs text-gray-500 pt-2">
                Já tem uma conta? <a href="/login" class="text-emerald-600 font-semibold hover:underline">Entrar</a>
            </p>
        </div>

    </div>

</body>
</html>