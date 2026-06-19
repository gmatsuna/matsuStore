<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-8 w-full">

    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-md">
        <div class="max-w-md">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Moda & Estilo Urbano</h2>
            <p class="mt-3 text-lg text-indigo-100">Confira as melhores ofertas com frete grÃ¡tis para todo o Brasil neste mÃªs.</p>
            <div class="mt-5">
                <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 shadow-sm transition">Aproveitar Oferta</a>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Nossos Produtos</h1>
        <span class="text-sm text-gray-500"><?= count($products) ?> itens encontrados</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($products as $product): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col">
                <div class="bg-gray-100 h-48 w-full overflow-hidden flex items-center justify-center text-gray-400 font-medium">
                    <?php if (!empty($product->image) && file_exists(__DIR__ . '/../../public/assets/img/' . $product->image)): ?>
                        <img src="/assets/img/<?= $product->image ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                    <?php else: ?>
                        <span class="text-sm">⚾ Imagem indisponível</span>
                    <?php endif; ?>
                </div>
                
                <div class="p-4 flex flex-col flex-1 space-y-2">
                    <span class="text-xs font-semibold text-indigo-600 tracking-wider uppercase">Destaque</span>
                    <h2 class="text-lg font-bold text-gray-900 line-clamp-1"><?= htmlspecialchars($product->name) ?></h2>
                    <p class="text-sm text-gray-500 flex-1 line-clamp-2"><?= htmlspecialchars($product->description) ?></p>
                    
                    <div class="pt-4 flex items-center justify-between border-t border-gray-100 mt-2">
                        <span class="text-xl font-black text-gray-900">R$ <?= number_format($product->price, 2, ',', '.') ?></span>
                        <a href="/produto?id=<?= (string)$product->_id ?>" class="bg-gray-900 text-white hover:bg-indigo-600 px-3 py-2 rounded-lg text-sm font-semibold transition text-center">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>