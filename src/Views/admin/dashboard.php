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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="w-full bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Novos Clientes</h3>
                    <p class="text-xs text-gray-400 font-medium">Cadastros ao longo do tempo</p>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">📈 Crescimento</span>
            </div>
            
            <div class="relative w-full h-[300px] sm:h-[350px]">
                <canvas id="novosClientesChart"></canvas>
            </div>
        </div>

        <div class="w-full bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Itens Mais Vendidos</h3>
                    <p class="text-xs text-gray-400 font-medium">Quantidade vendida por produto nos últimos 30 dias</p>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">⭐ Campeões</span>
            </div>
            
            <div class="relative w-full h-[300px] sm:h-[400px]">
                <canvas id="vendasItensChart"></canvas>
            </div>
        </div>

    </div>

</div>

<script>
    // ----------------------------------------------------
    // GRÁFICO 1: Novos Clientes (Linha - Visão Diária)
    // ----------------------------------------------------
    const labelsClientes = <?= json_encode($labelsClientes) ?>; // Agora conterá os dias (ex: "01/07", "02/07"...)
    const dadosClientes = <?= json_encode($valoresClientes) ?>;

    const ctxClientes = document.getElementById('novosClientesChart').getContext('2d');
    new Chart(ctxClientes, {
        type: 'line',
        data: {
            labels: labelsClientes,
            datasets: [{
                label: 'Novos Clientes',
                data: dadosClientes,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                borderWidth: 3,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false } 
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(243, 244, 246, 1)' },
                    ticks: { precision: 0 } // Evita mostrar números decimais para contagem de pessoas
                },
                x: { 
                    grid: { display: false },
                    ticks: {
                        font: {
                            family: 'ui-sans-serif, system-ui, sans-serif',
                            size: 10
                        },
                        // 🌟 INTELIGÊNCIA PARA GRÁFICO DIÁRIO:
                        // O autoSkip impede que os dias fiquem encavalados se houver muitos dados (ex: 30 dias).
                        // O Chart.js ocultará automaticamente alguns rótulos intermediários em telas pequenas.
                        autoSkip: true, 
                        maxTicksLimit: 15, // Limita a exibição de no máximo 15 datas simultâneas no eixo X
                        maxRotation: 45,   // Rotaciona levemente as datas se o espaço for muito apertado
                        minRotation: 0
                    }
                }
            }
        }
    });

    // ----------------------------------------------------
    // GRÁFICO 2: Vendas por Item (Barras Horizontais)
    // ----------------------------------------------------
    const labelsProdutos = <?= json_encode($labelsProdutos) ?>;
    const dadosProdutos = <?= json_encode($valoresProdutos) ?>;

    const ctxProdutos = document.getElementById('vendasItensChart').getContext('2d');
    new Chart(ctxProdutos, {
        type: 'bar', // Gráfico de barras
        data: {
            labels: labelsProdutos,
            datasets: [{
                label: 'Itens Vendidos',
                data: dadosProdutos,
                // Usando uma paleta azul moderna e elegante para diferenciar do verde
                backgroundColor: 'rgba(59, 130, 246, 0.85)',
                hoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                borderRadius: 6, // Cantos arredondados nas barras
                borderWidth: 0
            }]
        },
        options: {
            indexAxis: 'y', // 🌟 Transforma o gráfico de barras em HORIZONTAL!
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(243, 244, 246, 1)' },
                    ticks: { precision: 0 } // Mostra apenas números inteiros
                },
                y: {
                    grid: { display: false },
                    ticks: {
                        font: {
                            family: 'ui-sans-serif, system-ui, sans-serif',
                            weight: '600'
                        },
                        // 🌟 ADICIONE ESTA FUNÇÃO CALLBACK:
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0,
                        callback: function(value) {
                            const label = this.getLabelForValue(value);
                            
                            // Se a tela for pequena (celulares < 640px), encurta o nome para não cortar
                            if (window.innerWidth < 640 && label.length > 20) {
                                return label.substring(0, 17) + '...';
                            }
                            
                            // Para telas maiores, se o texto tiver mais de 25 caracteres, quebra em linhas
                            if (label.length > 25) {
                                return label.match(/.{1,25}(\s|$)/g) || [label];
                            }
                            
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>