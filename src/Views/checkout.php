<?php require __DIR__ . '/partials/header.php'; ?>

<div class="max-w-3xl mx-auto w-full py-12 px-4 flex-1">
    <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-8 uppercase">Finalizar Compra</h2>

    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Dados do Cartão</h3>

        <form id="payment-form">
            <div id="payment-element" class="mb-6">
                </div>

            <div id="error-message" class="text-red-600 text-sm font-semibold mb-4 hidden"></div>

            <button id="submit-button" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                <span id="button-text">Pagar Agora</span>
                <span id="spinner" class="hidden">🔄</span>
            </button>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
    // Configura o Stripe Elements com sua chave pública
    const stripe = Stripe('<?= getenv('STRIPE_PUBLISHABLE_KEY') ?>');
    const clientSecret = "<?= $clientSecret ?>";

    // Combina as cores do formulário do Stripe com o Tailwind da MatsuStore
    const appearance = {
        theme: 'stripe',
        variables: {
            colorPrimary: '#10b981', // Emerald-600
            colorBackground: '#ffffff',
            colorText: '#1f2937',
            borderRadius: '12px',
        }
    };

    const elements = stripe.elements({ clientSecret, appearance });
    
    // Cria o elemento unificado de cartão
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const form = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-button');
    const spinner = document.getElementById('spinner');
    const errorMsg = document.getElementById('error-message');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
        errorMsg.classList.add('hidden');

        // Envia o pagamento direto para confirmação no Stripe
        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                // Rota que criamos no Passo 3 para onde o usuário será redirecionado
                return_url: window.location.origin + "/checkout/sucesso",
            },
        });

        // Caso ocorra falha de saldo, digitação errada etc.
        if (error) {
            errorMsg.textContent = error.message;
            errorMsg.classList.remove('hidden');
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
        }
    });
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>