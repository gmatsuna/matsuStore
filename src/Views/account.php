<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-8 w-full">
    
    <section class="bg-gradient-to-r from-emerald-700 to-teal-900 rounded-2xl p-8 text-white shadow-sm relative overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        
        <div class="max-w-sm relative z-10 space-y-3 md:pr-4">
            <span class="text-xs font-bold uppercase tracking-widest bg-emerald-500 text-white px-2.5 py-1 rounded-full bg-opacity-30 inline-block">Pure Baseball</span>
            <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Olá, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Usuário') ?>!</h1>
            <p class="text-emerald-100 text-sm leading-relaxed max-w-xs">
                Sua conta está ativa e protegida. Você faz parte da matsuStore.
            </p>
        </div>

        <div class="absolute right-4 bottom-2 opacity-10 pointer-events-none select-none hidden sm:block">
            <span class="text-7xl lg:text-8xl font-black tracking-tighter">MatsuStore</span>
        </div>

    </section>

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'pedido_realizado'): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in">
            <span class="text-xl">🎉</span>
            <div>
                <p class="font-bold">Pedido realizado com sucesso!</p>
                <p class="text-sm opacity-90">Obrigado pela sua compra. Seu pedido já foi registrado no sistema.</p>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-200 p-4 bg-gray-50">
            <h2 class="text-lg font-bold tracking-tight text-gray-900">Informações do seu Perfil</h2>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Dados da Conta</span>
        </div>
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider">Nome Cadastrado</label>
                <p class="text-base font-semibold text-gray-800 mt-1 flex items-center gap-2">
                    <span>👤</span> <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Não informado') ?>
                </p>
            </div>
            
            <div class="border-t border-gray-100 pt-4">
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider">E-mail de Acesso</label>
                <p class="text-base font-semibold text-gray-800 mt-1 flex items-center gap-2">
                    <span>✉️</span> <?= htmlspecialchars($_SESSION['user']['email'] ?? $_SESSION['user']['id']) ?>
                </p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
    
            <?php if (isset($_SESSION['user']['isadmin']) && $_SESSION['user']['isadmin']): ?>
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">📊</span>
                        <div>
                            <h4 class="text-sm font-bold text-purple-900">Painel do Administrador</h4>
                            <p class="text-xs text-purple-700">Gerencie usuários, relatórios financeiros e dados estratégicos.</p>
                        </div>
                    </div>
                    <a href="/admin/dashboard" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg transition shadow-sm">
                        Painel ADM ➔
                    </a>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'employee'): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">📦</span>
                        <div>
                            <h4 class="text-sm font-bold text-blue-900">Área do Funcionário</h4>
                            <p class="text-xs text-blue-700">Gerencie o estoque e produtos da MatsuStore.</p>
                        </div>
                    </div>
                    <a href="/employee" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg transition shadow-sm">
                        Acessar Painel ➔
                    </a>
                </div>
            <?php endif; ?>
            
        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-200 p-4 bg-gray-50">
            <h2 class="text-lg font-bold tracking-tight text-gray-900">Meu Histórico de Pedidos</h2>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Compras</span>
        </div>

        <div class="p-6">
            <?php if (empty($orders)): ?>
                <div class="text-center py-12 border-2 border-dashed border-gray-100 rounded-xl">
                    <span class="text-4xl block mb-3">📦</span>
                    <p class="text-gray-500 font-medium">Você ainda não realizou nenhum pedido.</p>
                    <a href="/" class="inline-block mt-4 text-sm font-bold text-emerald-600 hover:underline">Ir às compras ➔</a>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($orders as $order): ?>
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:border-gray-300 transition">
                            
                            <div class="bg-gray-50 p-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-4 text-xs">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-gray-600">
                                    <div>
                                        <p class="uppercase font-bold text-gray-400 tracking-wider">Data</p>
                                        <p class="text-gray-900 font-semibold">
                                            <?= isset($order['created_at']) ? $order['created_at']->toDateTime()->format('d/m/Y H:i') : 'N/A' ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="uppercase font-bold text-gray-400 tracking-wider">Total</p>
                                        <p class="text-emerald-600 font-black">R$ <?= number_format($order['total'], 2, ',', '.') ?></p>
                                    </div>
                                    <div>
                                        <p class="uppercase font-bold text-gray-400 tracking-wider">Pagamento</p>
                                        <p class="text-gray-900 font-semibold uppercase"><?= htmlspecialchars($order['payment_method']) ?></p>
                                    </div>
                                </div>

                                <div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                        ⏱️ <?= htmlspecialchars($order['status'] ?? 'pendente') ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 divide-y divide-gray-100 bg-white">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-3">
                                            <?php if (!empty($item['image'])): ?>
                                                <img src="/assets/img/<?= ltrim(htmlspecialchars($item['image']), '/') ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-12 h-12 object-cover rounded-lg border border-gray-100">
                                            <?php else: ?>
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-lg">📦</div>
                                            <?php endif; ?>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($item['name']) ?></h4>
                                                <p class="text-xs text-gray-500">Quantidade: <?= $item['quantity'] ?> × R$ <?= number_format($item['price'], 2, ',', '.') ?></p>
                                            </div>
                                        </div>
                                        <span class="font-semibold text-gray-900 text-sm">
                                            R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>