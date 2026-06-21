<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-8 w-full">

    <section class="bg-gradient-to-r from-emerald-700 to-teal-900 rounded-2xl p-8 text-white shadow-sm relative overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
    
        <div class="max-w-md relative z-10 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest bg-emerald-500 text-white px-2.5 py-1 rounded-full bg-opacity-30 inline-block">Pure Baseball</span>
            <h2 class="text-3xl font-black tracking-tight sm:text-4xl">Viva a Paixão do Baseball Japonês</h2>
            <p class="text-emerald-100 text-sm leading-relaxed max-w-sm">
                Equipamentos profissionais das grandes marcas da NPB. Garanta mantos oficiais, tacos de Maple e luvas exclusivas com frete grátis para todo o Brasil.
            </p>
        </div>

        <div class="absolute right-4 bottom-2 opacity-10 pointer-events-none select-none hidden sm:block">
            <span class="text-7xl lg:text-8xl font-black tracking-tighter">MatsuStore</span>
        </div>

    </section>

    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Nossos Produtos</h1>
        <span class="text-sm text-gray-500"><?= count($products) ?> itens encontrados</span>
    </div>

    <div id="grid-produtos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php require __DIR__ . '/partials/product_grid.php'; ?>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputBusca = document.querySelector('input[name="busca"]');
        const gridProdutos = document.getElementById('grid-produtos');
        let timeout = null;

        if (inputBusca) {
            // Remove o comportamento padrão do form de recarregar a página ao dar Enter
            inputBusca.closest('form').addEventListener('submit', (e) => e.preventDefault());

            inputBusca.addEventListener('input', (e) => {
                const termo = e.target.value;

                // Debounce: Evita disparar requisições pro banco a cada mísera letra digitada.
                // Espera o usuário parar de digitar por 300 milissegundos para mandar a busca.
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    
                    // Faz a requisição em segundo plano adicionando um cabeçalho identificador
                    fetch(`/?busca=${encodeURIComponent(termo)}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Atualiza apenas a grade de produtos com o retorno do servidor
                        gridProdutos.innerHTML = html;
                    })
                    .catch(err => console.error('Erro na busca:', err));

                }, 300);
            });
        }
    });
</script>