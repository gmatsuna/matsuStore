<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-6 w-full">
    
    <div class="flex items-center gap-2 border-b border-gray-200 pb-4">
        <span class="text-2xl">🔒</span>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Finalizar Pedido</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <form id="form-checkout" action="/pedido/finalizar" method="POST" class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="text-emerald-600 font-bold">01</span>
                    <h2 class="text-lg font-bold text-gray-900">Endereço de Entrega</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">CEP</label>
                        <input type="text" name="cep" required placeholder="00000-000"
                               class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition font-semibold">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Logradouro (Rua/Avenida)</label>
                        <input type="text" name="endereco" required placeholder="Ex: Rua dos Rebatedores"
                               class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Número</label>
                        <input type="text" name="numero" required placeholder="123"
                               class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Complemento</label>
                        <input type="text" name="complemento" placeholder="Apto, Bloco, etc. (Opcional)"
                               class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Bairro</label>
                        <input type="text" name="bairro" required
                               class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Cidade</label>
                        <input type="text" name="cidade" required
                               class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Estado</label>
                        <input type="text" name="estado" required placeholder="SP" maxlength="2"
                               class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition uppercase text-center font-semibold">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="text-emerald-600 font-bold">02</span>
                    <h2 class="text-lg font-bold text-gray-900">Forma de Pagamento</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-between p-4 border border-emerald-600 bg-emerald-50/30 rounded-xl cursor-pointer select-none">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📱</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Pix</p>
                                <p class="text-xs text-gray-500">Aprovação imediata</p>
                            </div>
                        </div>
                        <input type="radio" name="metodo_pagamento" value="pix" checked class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                    </label>

                    <label class="relative flex items-center justify-between p-4 border border-gray-200 hover:border-gray-300 rounded-xl cursor-pointer select-none">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">💳</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Cartão de Crédito</p>
                                <p class="text-xs text-gray-500">Em até 12x sem juros</p>
                            </div>
                        </div>
                        <input type="radio" name="metodo_pagamento" value="cartao" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                    </label>
                </div>
            </div>

            <div class="block lg:hidden">
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-6 rounded-xl transition shadow-md uppercase tracking-wider text-sm">
                    Confirmar e Pagar R$ <?= number_format($subtotal, 2, ',', '.') ?>
                </button>
            </div>
        </form>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4 sticky top-24">
            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Resumo do Pedido</h2>
            
            <div class="divide-y divide-gray-100 max-h-48 overflow-y-auto pr-1 space-y-2">
                <?php foreach ($cartItems as $item): ?>
                    <div class="flex items-center justify-between text-sm pt-2 first:pt-0">
                        <div class="min-w-0 flex-1 pr-2">
                            <p class="font-bold text-gray-800 truncate"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-xs text-gray-500">Qtd: <?= $item['quantity'] ?></p>
                        </div>
                        <span class="font-semibold text-gray-900 shrink-0">
                            R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-gray-100 pt-4 space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Subtotal dos itens</span>
                    <span class="font-semibold text-gray-900">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Frete</span>
                    <span class="text-emerald-600 font-bold uppercase text-xs bg-emerald-50 px-2 py-0.5 rounded-full">Grátis</span>
                </div>
                <div class="border-t border-gray-100 pt-3 flex justify-between items-baseline">
                    <span class="text-base font-bold text-gray-900">Total a pagar</span>
                    <span class="text-2xl font-black text-emerald-600">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
            </div>

            <div class="hidden lg:block pt-2">
                <button type="button" onclick="document.getElementById('form-checkout').submit();" class="w-full bg-gray-900 hover:bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition shadow-sm uppercase tracking-wider text-center">
                    Finalizar Pedido ➔
                </button>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>