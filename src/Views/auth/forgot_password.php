<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        
        <div class="text-center">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                Recuperar Acesso ⚾
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Insira o e-mail da sua conta para receber as instruções de redefinição de senha.
            </p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <div class="flex">
                    <div class="text-sm text-red-700 font-medium">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl">
                <div class="flex">
                    <div class="text-sm text-emerald-700 font-medium">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="/esqueci-senha" method="POST">
            <div class="rounded-md space-y-4">
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wide text-gray-700 mb-1">
                        Endereço de E-mail
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="appearance-none relative block w-full px-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 text-sm transition bg-gray-50 focus:bg-white" 
                        placeholder="seu-email@exemplo.com"
                    >
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gray-900 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200"
                >
                    Enviar Link de Recuperação ➔
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="/login" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">
                ← Voltar para o Login
            </a>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>