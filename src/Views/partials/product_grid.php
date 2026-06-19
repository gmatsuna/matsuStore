<!-- src/Views/partials/product_grid.php -->
<?php if (empty($products)): ?>
    <div class="col-span-full text-center py-12 text-gray-500">
        ⚾ Nenhum produto encontrado para a sua busca.
    </div>
<?php else: ?>
    <?php foreach ($products as $product): ?>
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 border border-gray-100 flex flex-col justify-between">
            <div class="bg-gray-100 h-48 w-full overflow-hidden flex items-center justify-center text-gray-400 font-medium">
                <?php if (!empty($product->image) && file_exists(__DIR__ . '/../../../public/assets/img/' . $product->image)): ?>
                    <img src="/assets/img/<?= $product->image ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                <?php else: ?>
                    <span class="text-sm">⚾ Imagem indisponível</span>
                <?php endif; ?>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wide"><?= htmlspecialchars($product->category) ?></span>
                    <h3 class="font-bold text-gray-900 mt-1 line-clamp-2"><?= htmlspecialchars($product->name) ?></h3>
                    <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($product->team) ?></p>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="font-black text-gray-900 text-lg">R$ <?= number_format($product->price, 2, ',', '.') ?></span>
                    <a href="/produto?id=<?= (string)$product->_id ?>" class="bg-gray-900 text-white hover:bg-emerald-600 px-3 py-2 rounded-lg text-sm font-semibold transition text-center">
                        Ver Detalhes
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>