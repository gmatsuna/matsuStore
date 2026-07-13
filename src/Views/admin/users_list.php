<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/sidebar.php'; ?>

<div class="space-y-6 w-full">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 pb-4">
        <div class="flex items-center gap-4">
            <a href="/admin/dashboard" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-3 py-2 rounded-lg transition shrink-0">
                ⬅ Voltar
            </a>
            <div class="flex items-center gap-2">
                <span class="text-2xl">👥</span>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Gerenciamento de Usuários</h1>
            </div>
        </div>
        <span class="text-sm font-semibold text-gray-500 shrink-0"><?= count($users) ?> Usuários Cadastrados</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Novo Usuário</h2>
            <form action="/admin/usuarios/salvar" method="POST" class="space-y-4 text-xs">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Nome Completo</label>
                    <input type="text" name="name" required placeholder="Ex: Gilberto Matsunaga" class="w-full border border-gray-200 rounded-xl p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">E-mail de Acesso</label>
                    <input type="email" name="email" required placeholder="exemplo@matsu.com" class="w-full border border-gray-200 rounded-xl p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Senha Provisória</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full border border-gray-200 rounded-xl p-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Função (Role)</label>
                        <select name="role" class="w-full border border-gray-200 rounded-xl p-2 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition font-medium text-xs">
                            <option value="client">Client</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Admin?</label>
                        <select name="isadmin" class="w-full border border-gray-200 rounded-xl p-2 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition font-medium text-xs">
                            <option value="0">Não</option>
                            <option value="1">Sim 👑</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-4 rounded-xl transition text-center shadow-sm uppercase tracking-wider text-[10px]">
                    Criar Usuário ➔
                </button>
            </form>
        </div>

        <div class="lg:col-span-9 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                            <th class="p-4">Nome Completo</th>
                            <th class="p-4">E-mail</th>
                            <th class="p-4 text-center">Role</th>
                            <th class="p-4 text-center">Admin</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                        <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-bold text-gray-900">
                                    <?= htmlspecialchars($u['name'] ?? 'Sem Nome') ?>
                                </td>
                                <td class="p-4 text-gray-500">
                                    <?= htmlspecialchars($u['email'] ?? '-') ?>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <?php if (($u['role'] ?? '') === 'employee'): ?>
                                        <span class="inline-block text-[10px] font-bold uppercase bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">Employee</span>
                                    <?php else: ?>
                                        <span class="inline-block text-[10px] font-bold uppercase bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">Client</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <?php if (isset($u['isadmin']) && ($u['isadmin'] === true || $u['isadmin'] == 1)): ?>
                                        <span class="inline-block text-[10px] font-bold uppercase bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full">Sim 👑</span>
                                    <?php else: ?>
                                        <span class="inline-block text-[10px] font-medium bg-rose-50 text-rose-500 px-2.5 py-1 rounded-full">Não</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <form action="/admin/usuarios/toggle-status" method="POST" class="inline m-0">
                                        <input type="hidden" name="id" value="<?= (string)$u['_id'] ?>">
                                        <?php 
                                            $isAtivo = isset($u['isativo']) ? (bool)$u['isativo'] : true; 
                                        ?>
                                        <?php if ($isAtivo): ?>
                                            <button type="submit" class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase bg-emerald-100 hover:bg-emerald-200 text-emerald-800 px-3 py-1.5 rounded-full transition" title="Clique para desativar">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Ativo
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase bg-rose-100 hover:bg-rose-200 text-rose-800 px-3 py-1.5 rounded-full transition" title="Clique para ativar">
                                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Inativo
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <button 
                                            type="button"
                                            onclick="openEditModal('<?= (string)$u['_id'] ?>', '<?= addslashes($u['name']) ?>', '<?= addslashes($u['email']) ?>', '<?= $u['role'] ?? 'client' ?>', '<?= isset($u['isadmin']) && $u['isadmin'] ? '1' : '0' ?>')"
                                            class="bg-gray-100 hover:bg-purple-100 hover:text-purple-700 text-gray-600 text-xs font-bold px-2.5 py-1.5 rounded-lg transition"
                                        >
                                            ✏️ Editar
                                        </button>

                                        <form action="/admin/usuarios/deletar" method="POST" onsubmit="return confirm('Tem certeza absoluta que deseja remover o usuário <?= addslashes($u['name']) ?>? Esta ação não pode ser desfeita.');" class="m-0">
                                            <input type="hidden" name="id" value="<?= (string)$u['_id'] ?>">
                                            <button 
                                                type="submit" 
                                                class="bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white text-xs font-bold px-2.5 py-1.5 rounded-lg transition"
                                            >
                                                🗑️ Excluir
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

<div id="editModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xl max-w-md w-full p-6 space-y-4 relative animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">✏️ Editar Cadastro</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
        </div>
        
        <form action="/admin/usuarios/atualizar" method="POST" class="space-y-4 text-sm">
            <input type="hidden" id="edit_id" name="id">

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Nome Completo</label>
                <input type="text" id="edit_name" name="name" required class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">E-mail de Acesso</label>
                <input type="email" id="edit_email" name="email" required class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Nova Senha (Deixe em branco para não alterar)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Função (Role)</label>
                    <select id="edit_role" name="role" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition font-medium">
                        <option value="client">Client</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Administrador?</label>
                    <select id="edit_isadmin" name="isadmin" class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-600 outline-none transition font-medium">
                        <option value="0">Não</option>
                        <option value="1">Sim 👑</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-xl transition text-xs uppercase">
                    Cancelar
                </button>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-5 rounded-xl transition shadow-sm text-xs uppercase tracking-wider">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name, email, role, isadmin) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_isadmin').value = isadmin;
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>