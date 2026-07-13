<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-8 w-full">
    
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div class="flex items-center gap-2">
            <span class="text-2xl">📦</span>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Painel Operacional</h1>
        </div>
        <span class="text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700 px-3 py-1 rounded-full">MatsuStore Funcionário</span>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold">
            🎉 <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold">
            ❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Cadastrar Novo Produto</h2>
            <form action="/employee/produto/salvar" method="POST" class="space-y-4 text-sm">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Nome do Item</label>
                    <input type="text" name="name" required placeholder="Ex: Luva SSK Proedge" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Preço (R$)</label>
                        <input type="number" step="0.01" name="price" required placeholder="1499.90" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Estoque Inicial</label>
                        <input type="number" name="stock" required placeholder="10" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Categoria</label>
                    <input type="text" name="category" placeholder="Ex: Luvas, Bastões" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Nome do Arquivo de Imagem</label>
                    <input type="text" name="image" placeholder="Ex: luva_ssk.png" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition text-center shadow-sm uppercase tracking-wider text-xs">
                    Gravar Produto ➔
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900">Gerenciamento de Inventário</h2>
                <span class="text-xs font-semibold text-gray-500"><?= count($products) ?> Itens</span>
            </div>

            <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto">
                <?php foreach ($products as $prod): ?>
                    <div class="p-4 flex flex-wrap items-center justify-between gap-4 text-sm">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-lg shrink-0 overflow-hidden border border-gray-100">
                                <?php if(!empty($prod['image'])): ?>
                                    <img src="/assets/img/<?= htmlspecialchars($prod['image']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    📦
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-gray-800 truncate"><?= htmlspecialchars($prod['name']) ?></h4>
                                <p class="text-xs text-blue-600 font-semibold">R$ <?= number_format($prod['price'], 2, ',', '.') ?></p>
                            </div>
                        </div>

                        <form action="/employee/produto/atualizar-estoque" method="POST" class="flex items-center gap-2 shrink-0">
                            <input type="hidden" name="product_id" value="<?= (string)$prod['_id'] ?>">
                            <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 px-2">
                                <span class="text-xs font-bold text-gray-400 uppercase mr-1">Qtd:</span>
                                <input type="number" name="stock" value="<?= $prod['stock'] ?>" class="w-16 p-2 bg-transparent text-center font-bold text-gray-800 outline-none text-xs" min="0">
                            </div>
                            <button type="submit" class="bg-gray-900 hover:bg-blue-600 text-white p-2.5 rounded-xl transition text-xs font-bold">
                                Salvar
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>