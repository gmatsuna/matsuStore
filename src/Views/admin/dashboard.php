<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/sidebar.php'; ?>

<div class="space-y-8 w-full">
    
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div class="flex items-center gap-2">
            <span class="text-2xl">👑</span>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Painel do Administrador</h1>
        </div>
        <span class="text-xs font-bold uppercase tracking-wider bg-purple-100 text-purple-700 px-3 py-1 rounded-full">MatsuStore Admin</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <a href="/admin/usuarios" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between hover:border-purple-400 hover:shadow transition group">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total de Usuários</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1"><?= $totalUsers ?? 0 ?></h3>
                <span class="text-xs text-purple-600 font-medium group-hover:underline">Visualizar lista ➔</span>
            </div>
            <span class="text-3xl bg-purple-50 p-3 rounded-lg text-purple-600">👥</span>
        </a>

        <a href="/admin/produtos" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between hover:border-blue-400 hover:shadow transition group">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Produtos Cadastrados</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1"><?= $totalProducts ?? 0 ?></h3>
                <span class="text-xs text-blue-600 font-medium group-hover:underline">Gerenciar catálogo ➔</span>
            </div>
            <span class="text-3xl bg-blue-50 p-3 rounded-lg text-blue-600">📦</span>
        </a>

    </div>

    <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl p-12 text-center text-sm text-gray-500">
        📊 Gráficos de entrada/saída de produtos e relatórios de cadastros serão renderizados aqui.
    </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>