<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/sidebar.php'; ?>

<div class="space-y-6 w-full">
    
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div class="flex items-center gap-4">
            <a href="/admin/dashboard" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-3 py-2 rounded-lg transition">
                ⬅ Voltar
            </a>
            <div class="flex items-center gap-2">
                <span class="text-2xl">📦</span>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Gerenciamento de Produtos</h1>
            </div>
        </div>
        <span class="text-xs font-semibold text-gray-500"><?= count($products) ?> Produtos no Catálogo</span>
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
            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Novo Produto</h2>
            <form action="/admin/produtos/salvar" method="POST" class="space-y-4 text-sm">
                
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Nome do Produto</label>
                    <input type="text" name="name" required placeholder="Ex: Luva Pro M" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Categoria (Digite ou Selecione)</label>
                    <input type="text" name="category" list="categories_list" required placeholder="Digite ou selecione uma categoria" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition font-medium">
                    <datalist id="categories_list">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>"></option>
                        <?php endforeach; ?>
                        <?php if(empty($categories)): ?>
                            <option value="Tacos (Bats)"></option>
                            <option value="Luvas (Gloves)"></option>
                            <option value="Bolas (Balls)"></option>
                            <option value="Vestuário (Apparel)"></option>
                            <option value="Acessórios"></option>
                        <?php endif; ?>
                    </datalist>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Preço (R$)</label>
                        <input type="number" name="price" step="0.01" min="0" required placeholder="0.00" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Qtd. Estoque</label>
                        <input type="number" name="stock" min="0" required placeholder="0" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Imagem do Produto (Lida da Pasta)</label>
                    <select name="image" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition font-medium">
                        <option value="">Sem Imagem ⚾</option>
                        <?php foreach ($availableImages as $img): ?>
                            <option value="<?= htmlspecialchars($img) ?>"><?= htmlspecialchars($img) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">💡 Para adicionar novas opções de imagem, basta colocar os arquivos na pasta <code>public/assets/img/</code> do projeto.</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition text-center shadow-sm uppercase tracking-wider text-xs">
                    Cadastrar Produto ➔
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold uppercase text-gray-400 tracking-wider">
                            <th class="p-4">Produto</th>
                            <th class="p-4 text-center">Categoria</th>
                            <th class="p-4 text-right">Preço</th>
                            <th class="p-4 text-center">Estoque</th>
                            <th class="p-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 bg-gray-50/50">
                                    Nenhum produto cadastrado no momento.
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($products as $p): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center shrink-0">
                                        <?php if (!empty($p['image'])): ?>
                                            <img src="/assets/img/<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs">⚾</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900"><?= htmlspecialchars($p['name'] ?? 'Sem Nome') ?></div>
                                        <div class="text-xs text-gray-400 font-normal">ID: <?= (string)$p['_id'] ?></div>
                                    </div>
                                </td>
                                
                                <td class="p-4 text-center">
                                    <span class="inline-block text-xs font-semibold bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full uppercase">
                                        <?= htmlspecialchars($p['category'] ?? 'Geral') ?>
                                    </span>
                                </td>
                                
                                <td class="p-4 text-right font-bold text-gray-900">
                                    R$ <?= number_format((float)($p['price'] ?? 0), 2, ',', '.') ?>
                                </td>
                                
                                <td class="p-4 text-center">
                                    <?php 
                                        $stock = (int)($p['stock'] ?? 0);
                                        if ($stock <= 5): 
                                    ?>
                                        <span class="inline-block text-xs font-bold bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full">
                                            <?= $stock ?> (Crítico) 🚨
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block text-xs font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full">
                                            <?= $stock ?> un
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="p-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <button 
                                            onclick='openEditModal(<?= json_encode([
                                                "id" => (string)$p["_id"],
                                                "name" => htmlspecialchars($p["name"] ?? ""),
                                                "category" => htmlspecialchars($p["category"] ?? ""),
                                                "price" => (float)($p["price"] ?? 0),
                                                "stock" => (int)($p["stock"] ?? 0),
                                                "image" => htmlspecialchars($p["image"] ?? "")
                                            ]) ?>)'
                                            class="p-1.5 hover:bg-amber-50 hover:text-amber-600 rounded-lg text-gray-500 transition"
                                            title="Editar Produto"
                                        >
                                            ✏️
                                        </button>
                                        
                                        <span class="text-gray-200">|</span>
                                        
                                        <form action="/admin/produtos/deletar" method="POST" onsubmit="return confirmDelete('<?= htmlspecialchars($p['name'] ?? 'este produto') ?>')" class="inline m-0">
                                            <input type="hidden" name="id" value="<?= (string)$p['_id'] ?>">
                                            <button type="submit" class="p-1.5 hover:bg-rose-50 hover:text-rose-600 rounded-lg text-gray-500 transition" title="Excluir Produto">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<div id="editProductModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 transition-opacity">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xl max-w-md w-full overflow-hidden transform scale-95 transition-transform duration-200">
        
        <div class="flex items-center justify-between border-b border-gray-100 p-5 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Editar Produto</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
        </div>

        <form action="/admin/produtos/atualizar" method="POST" class="p-5 space-y-4 text-sm">
            <input type="hidden" name="id" id="edit_id">

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Nome do Produto</label>
                <input type="text" name="name" id="edit_name" required class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Categoria</label>
                <input type="text" name="category" id="edit_category" list="categories_list" required class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition font-medium">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Preço (R$)</label>
                    <input type="number" name="price" id="edit_price" step="0.01" min="0" required class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Qtd. Estoque</label>
                    <input type="number" name="stock" id="edit_stock" min="0" required class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Imagem do Produto (Lida da Pasta)</label>
                <select name="image" id="edit_image" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition font-medium">
                    <option value="">Sem Imagem ⚾</option>
                    <?php foreach ($availableImages as $img): ?>
                        <option value="<?= htmlspecialchars($img) ?>"><?= htmlspecialchars($img) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition text-center uppercase tracking-wider text-xs">
                    Cancelar
                </button>
                <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-center shadow-sm uppercase tracking-wider text-xs">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmDelete(productName) {
        return confirm(`Tem certeza que deseja excluir o produto "${productName}"? Esta ação não pode ser desfeita.`);
    }

    function openEditModal(product) {
        // Preenche os campos da modal com os valores do produto
        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_category').value = product.category;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock').value = product.stock;
        document.getElementById('edit_image').value = product.image;

        // Exibe a modal adicionando classes do Tailwind
        const modal = document.getElementById('editProductModal');
        modal.classList.remove('hidden');
        modal.classList.add('opacity-100');
    }

    function closeEditModal() {
        // Oculta a modal
        const modal = document.getElementById('editProductModal');
        modal.classList.add('hidden');
        modal.classList.remove('opacity-100');
    }

    // Fecha a modal se o usuário clicar fora da caixa branca
    window.onclick = function(event) {
        const modal = document.getElementById('editProductModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>