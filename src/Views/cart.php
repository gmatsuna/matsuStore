<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<div class="space-y-6 w-full">
    <!-- BANNER PADRÃO MATSUSTORE (AJUSTE DE POSICIONAMENTO DO TEXTO) -->
    <section class="bg-gradient-to-r from-emerald-700 to-teal-900 rounded-2xl p-8 text-white shadow-sm relative overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        
        <!-- Ajustado de max-w-md para max-w-sm e adicionado md:pr-4 para afastar do 'M' -->
        <div class="max-w-sm relative z-10 space-y-3 md:pr-4">
            <span class="text-xs font-bold uppercase tracking-widest bg-emerald-500 text-white px-2.5 py-1 rounded-full bg-opacity-30 inline-block">Pure Baseball</span>
            <h2 class="text-3xl font-black tracking-tight sm:text-4xl">Seu Carrinho</h2>
            <!-- Forçado o max-w-[280px] ou max-w-xs para o texto quebrar mais cedo -->
            <p class="text-emerald-100 text-sm leading-relaxed max-w-xs">
                Gerencie os itens antes de fechar o seu pedido de baseball. Garanta mantos oficiais, tacos de Maple e luvas exclusivas com frete grátis para todo o Brasil.
            </p>
        </div>

        <div class="absolute right-4 bottom-2 opacity-10 pointer-events-none select-none hidden sm:block">
            <span class="text-7xl lg:text-8xl font-black tracking-tighter">MatsuStore</span>
        </div>

    </section>

        <span class="absolute right-0 -bottom-6 text-7xl md:text-8xl font-black text-emerald-900/15 select-none pointer-events-none font-sans uppercase tracking-normal translate-y-2">
            MatsuStore
        </span>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm w-full flex flex-col items-center justify-center space-y-4">
            
            <div class="flex justify-center w-full">
                <span class="text-5xl inline-block">⚾</span>
            </div>
            
            <h2 class="text-xl font-bold text-gray-900 w-full text-center">O seu carrinho está vazio</h2>
            
            <p class="text-sm text-gray-500 max-w-sm mx-auto w-full text-center">
                Você ainda não adicionou nenhum equipamento ou manto da NPB à sua sacola.
            </p>
            
            <div class="pt-2 w-full flex justify-center">
                <a href="/" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl transition text-sm shadow-sm">
                    Ver Produtos Disponíveis
                </a>
            </div>

        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($cartItems as $item): ?>
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm flex gap-4 items-center relative overflow-hidden">
                        
                        <div class="bg-gray-100 h-20 w-20 rounded-xl overflow-hidden shrink-0 flex items-center justify-center text-gray-400 font-medium text-xs">
                            <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../../public/assets/img/' . $item['image'])): ?>
                                <img src="/assets/img/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span>⚾</span>
                            <?php endif; ?>
                        </div>

                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider"><?= htmlspecialchars($item['category'] ?? '') ?></span>
                            <h3 class="font-bold text-gray-900 truncate text-sm sm:text-base"><?= htmlspecialchars($item['name']) ?></h3>
                            
                            <div class="flex items-center justify-between mt-2 flex-wrap gap-2">
                                <p class="text-xs text-gray-500">
                                    Preço unitário: <span class="font-semibold text-gray-800">R$ <?= number_format($item['price'], 2, ',', '.') ?></span>
                                </p>
                                <div class="text-xs text-gray-500 flex items-center gap-2">
                                    <span>Quantidade:</span>
                                    
                                    <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 overflow-hidden h-9">
                                        <button 
                                            type="button" 
                                            data-id="<?= $item['id'] ?>" 
                                            data-action="decrease"
                                            class="px-2.5 h-full font-bold text-gray-500 hover:bg-gray-200 hover:text-gray-800 transition select-none quantity-toggle-btn"
                                        >
                                            &minus;
                                        </button>

                                        <input 
                                            type="number" 
                                            value="<?= $item['quantity'] ?>" 
                                            min="1" 
                                            max="10" 
                                            readonly
                                            data-id="<?= $item['id'] ?>" 
                                            class="w-10 h-full text-center bg-transparent border-none font-bold text-gray-900 outline-none text-xs pointer-events-none dynamic-quantity-input"
                                        >

                                        <button 
                                            type="button" 
                                            data-id="<?= $item['id'] ?>" 
                                            data-action="increase"
                                            class="px-2.5 h-full font-bold text-gray-500 hover:bg-gray-200 hover:text-gray-800 transition select-none quantity-toggle-btn"
                                        >
                                            &plus;
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right flex flex-col justify-between items-end h-20 pl-2 border-l border-gray-100">
                            <span class="font-black text-gray-900 text-sm sm:text-base item-subtotal-display" data-id="<?= $item['id'] ?>">
                                R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                            </span>
                            
                            <!-- SUBISTITUA A TAG <a> DE REMOÇÃO POR ESTE FORMULÁRIO SEGURO: -->
                            <form action="/carrinho/remover" method="POST" class="inline">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 hover:underline transition flex items-center gap-0.5 bg-transparent border-none cursor-pointer">
                                    <span>❌</span> Remover
                                </button>
                            </form>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4 sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Resumo da Compra</h2>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal dos itens</span>
                        <span class="font-semibold text-gray-900 cart-subtotal-display">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Frete</span>
                        <span class="text-emerald-600 font-bold uppercase text-xs bg-emerald-50 px-2 py-0.5 rounded-full">Grátis</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 flex justify-between items-baseline">
                    <span class="text-base font-bold text-gray-900">Valor Total</span>
                    <span class="text-2xl font-black text-emerald-600 cart-total-display">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>

                <div class="pt-2 space-y-2">
                    <button class="w-full bg-gray-900 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl text-sm transition shadow-sm uppercase tracking-wider">
                        Finalizar Pedido ➔
                    </button>
                    <a href="/" class="block text-center text-xs font-semibold text-gray-500 hover:text-emerald-600 transition hover:underline">
                        Continuar Comprando
                    </a>
                </div>
            </div>

        </div>
    <?php endif; ?> </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const botoesQuantidade = document.querySelectorAll('.quantity-toggle-btn');
            
            botoesQuantidade.forEach(botao => {
                botao.addEventListener('click', (e) => {
                    const productId = e.target.getAttribute('data-id');
                    const acao = e.target.getAttribute('data-action');
                    
                    // Localiza o input numérico correspondente a este produto específico
                    const inputCorrespondente = document.querySelector(`.dynamic-quantity-input[data-id="${productId}"]`);
                    if (!inputCorrespondente) return;

                    let quantidadeAtual = parseInt(inputCorrespondente.value);

                    // Altera o valor local baseado no botão clicado
                    if (acao === 'increase' && quantidadeAtual < 10) {
                        quantidadeAtual++;
                    } else if (acao === 'decrease' && quantidadeAtual > 1) { // Garante limite mínimo de 1
                        quantidadeAtual--;
                    } else {
                        // Se tentou ir abaixo de 1 ou acima de 10, ignora e cancela o envio
                        return;
                    }

                    // Atualiza o valor visível no input imediatamente
                    inputCorrespondente.value = quantidadeAtual;

                    // Prepara a requisição POST para o servidor backend
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('quantity', quantidadeAtual);

                    // Envia a atualização em segundo plano
                    fetch('/carrinho/atualizar', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // 1. Atualiza o subtotal do item na listagem
                            const displaySubtotalItem = document.querySelector(`.item-subtotal-display[data-id="${productId}"]`);
                            if (displaySubtotalItem) displaySubtotalItem.textContent = data.itemSubtotal;

                            // 2. Atualiza o resumo financeiro lateral
                            const displaySubtotalCarrinho = document.querySelector('.cart-subtotal-display');
                            const displayTotalCarrinho = document.querySelector('.cart-total-display');
                            if (displaySubtotalCarrinho) displaySubtotalCarrinho.textContent = data.cartTotal;
                            if (displayTotalCarrinho) displayTotalCarrinho.textContent = data.cartTotal;

                            // 3. Atualiza o contador flutuante do topo (Header)
                            const badgeCarrinho = document.querySelector('.dynamic-cart-badge');
                            if (badgeCarrinho) badgeCarrinho.textContent = data.totalItems;
                        } else {
                            alert('Erro: ' + data.message);
                            inputCorrespondente.value = quantidadeAtual - 1;
                        }
                    })
                    .catch(err => console.error('Erro na requisição:', err));
                });
            });
        });
    </script>

<?php require __DIR__ . '/partials/footer.php'; ?>