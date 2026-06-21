<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-8 w-full">
    
    <section class="bg-gradient-to-r from-emerald-700 to-teal-900 rounded-2xl p-8 text-white shadow-sm relative overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
    
        <div class="max-w-md relative z-10 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest bg-emerald-500 text-white px-2.5 py-1 rounded-full bg-opacity-30 inline-block">Pure Baseball</span>
            <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Olá, <?= htmlspecialchars($user['name']) ?>!</h1>
            <p class="text-emerald-100 text-sm leading-relaxed max-w-sm">
                Sua conta está ativa e protegida. Você faz parte da matsuStore desde <?= htmlspecialchars($user['member_since']) ?>.
            </p>
        </div>

        <div class="absolute right-4 bottom-2 opacity-10 pointer-events-none select-none hidden sm:block">
            <span class="text-7xl lg:text-8xl font-black tracking-tighter">MatsuStore</span>
        </div>

    </section>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-200 p-4 bg-gray-50">
            <h2 class="text-lg font-bold tracking-tight text-gray-900">Informações do seu Perfil</h2>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Dados da Conta</span>
        </div>
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider">Nome Cadastrado</label>
                <p class="text-base font-semibold text-gray-800 mt-1 flex items-center gap-2">
                    <span>👤</span> <?= htmlspecialchars($user['name']) ?>
                </p>
            </div>
            
            <div class="border-t border-gray-100 pt-4">
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider">E-mail de Acesso</label>
                <p class="text-base font-semibold text-gray-800 mt-1 flex items-center gap-2">
                    <span>✉️</span> <?= htmlspecialchars($user['email']) ?>
                </p>
            </div>
        </div>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
