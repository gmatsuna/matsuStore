<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        
        <div class="text-center">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                Nova Senha ⚾
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Digite e confirme a sua nova credencial de acesso abaixo.
            </p>

            <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-800">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Redefinindo senha para: <span class="underline font-black"><?= htmlspecialchars($userEmail ?? '') ?></span>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <div class="text-sm text-red-700 font-medium">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            </div>
        <?php endif; ?>

        <form class="mt-6 space-y-6" action="/redefinir-senha" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <div class="rounded-md space-y-4">
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wide text-gray-700 mb-1">
                        Nova Senha
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        class="appearance-none relative block w-full px-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition bg-gray-50 focus:bg-white" 
                        placeholder="••••••••"
                    >
                </div>

                <div>
                    <label for="password_confirm" class="block text-xs font-bold uppercase tracking-wide text-gray-700 mb-1">
                        Confirme a Nova Senha
                    </label>
                    <input 
                        id="password_confirm" 
                        name="password_confirm" 
                        type="password" 
                        required 
                        class="appearance-none relative block w-full px-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition bg-gray-50 focus:bg-white" 
                        placeholder="••••••••"
                    >
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gray-900 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200"
                >
                    Atualizar Senha Geral ➔
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>