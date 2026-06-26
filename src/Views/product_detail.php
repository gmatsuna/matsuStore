<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-6 w-full">
    <a href="/" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition flex items-center gap-1">
        &larr; Voltar para a listagem
    </a>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
        
        <div class="bg-gray-100 rounded-xl h-96 w-full overflow-hidden flex items-center justify-center text-gray-400 font-medium">
            <?php if (!empty($product->image) && file_exists(__DIR__ . '/../../public/assets/img/' . $product->image)): ?>
                <img src="/assets/img/<?= $product->image ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-full object-contain">
            <?php else: ?>
                <span class="text-lg">⚾ Sem imagem disponível</span>
            <?php endif; ?>
        </div>

        <div class="flex flex-col justify-between py-2">
            <div class="space-y-4">
                <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase bg-emerald-50 px-2.5 py-1 rounded-full">Disponível</span>
                <h1 class="text-3xl font-black text-gray-900"><?= htmlspecialchars($product->name) ?></h1>
                <p class="text-2xl font-black text-gray-900">R$ <?= number_format($product->price, 2, ',', '.') ?></p>
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Descrição do Produto</h3>
                    <p class="mt-2 text-gray-600 leading-relaxed"><?= htmlspecialchars($product->description) ?></p>
                </div>
            </div>

            <form action="/carrinho/adicionar" method="POST" class="pt-6 border-t border-gray-100 mt-6 flex items-center gap-4">
                <input type="hidden" name="product_id" value="<?= (string)$product->_id ?>">
                
                <div class="w-20">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Qtd</label>
                    <input type="number" name="quantity" value="1" min="1" max="10" required 
                           class="w-full border border-gray-200 rounded-xl text-center p-3 bg-gray-50 font-bold focus:bg-white focus:ring-2 focus:ring-emerald-600 outline-none transition text-sm">
                </div>

                <div class="flex-1 pt-4">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-emerald-600 text-white font-bold py-3.5 px-6 rounded-xl transition shadow-md shadow-gray-100 flex items-center justify-center gap-2 text-sm">
                        <span>🛒</span> Adicionar ao Carrinho
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>