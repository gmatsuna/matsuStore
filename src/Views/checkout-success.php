<?php require __DIR__ . '/partials/header.php'; ?>

<div class="max-w-xl mx-auto w-full py-16 px-4 flex-1 flex flex-col justify-center items-center">
    
    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6 animate-bounce">
        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <h2 class="text-3xl font-black text-gray-950 text-center tracking-tight uppercase mb-3">
        Pagamento Aprovado!
    </h2>
    <p class="text-gray-500 text-center text-sm max-w-md mb-8 leading-relaxed">
        O seu pedido foi processado e concluído com sucesso. Agradecemos a preferência pela <strong class="text-gray-900">matsuStore</strong>!
    </p>

    <div class="w-full bg-white rounded-2xl border border-gray-150 p-6 shadow-sm mb-8">
        <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-3">
            <span class="text-xs text-gray-400 font-semibold uppercase">ID da Transação</span>
            <span class="text-xs font-mono text-gray-600 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-150">
                <?= htmlspecialchars(substr($paymentIntentId, 0, 18) . '...') ?>
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-xs text-gray-400 font-semibold uppercase">Status do Pedido</span>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-150">
                Concluído
            </span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full">
        <a href="/" class="flex-1 text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-xl transition shadow-sm">
            Voltar para a Loja
        </a>
        <a href="/minha-conta" class="flex-1 text-center bg-white hover:bg-gray-50 text-gray-700 font-bold py-3.5 px-6 rounded-xl transition border border-gray-200">
            Ver meus Pedidos
        </a>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>