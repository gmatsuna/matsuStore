<?php

namespace App\Controllers;

use App\Config\Database;

class AdminController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 🛡️ TRAVA DE SEGURANÇA MÁXIMA: Só entra se for isadmin === true
        if (!isset($_SESSION['user']) || ($_SESSION['user']['isadmin'] ?? false) !== true) {
            $_SESSION['error'] = "Acesso negado! Esta área é exclusiva para Administradores.";
            header('Location: /login');
            exit;
        }
    }

    public function dashboard()
    {
        $db = Database::getDatabase();

        $totalUsers = $db->users->countDocuments();
        $totalProducts = $db->products->countDocuments();

        // ==========================================
        // 📊 GRÁFICO 1: Novos Clientes (Visão Diária)
        // ==========================================
        $clientes = $db->users->find(['role' => 'client']);
        $agrupadoClientes = [];

        foreach ($clientes as $cliente) {
            $createdAt = null;
            $clienteArray = is_object($cliente) ? (array) $cliente : $cliente;

            if (isset($clienteArray['created_at'])) {
                $createdAt = $clienteArray['created_at'];
            } elseif (is_object($cliente) && isset($cliente->created_at)) {
                $createdAt = $cliente->created_at;
            }

            if ($createdAt instanceof \MongoDB\BSON\UTCDateTime) {
                $dateTime = $createdAt->toDateTime();
                $dateTime->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
                
                // 🌟 ALTERAÇÃO: Agora a chave guarda o dia também (Ano-Mês-Dia)
                $chave = $dateTime->format('Y-m-d'); 
                
                if (!isset($agrupadoClientes[$chave])) {
                    $agrupadoClientes[$chave] = 0;
                }
                $agrupadoClientes[$chave]++;
            }
        }

        // Ordena cronologicamente por data (ex: 2026-07-01 antes de 2026-07-02)
        ksort($agrupadoClientes);
        
        $labelsClientes = [];
        $valoresClientes = [];
        
        foreach ($agrupadoClientes as $chave => $total) {
            // Converte a chave 'YYYY-MM-DD' em um formato legível para o gráfico: 'DD/MM'
            $dataFormatada = date('d/m', strtotime($chave));
            
            $labelsClientes[] = $dataFormatada;
            $valoresClientes[] = $total;
        }

        // Fallback caso a coleção esteja vazia
        if (empty($labelsClientes)) {
            $labelsClientes = [date('d/m')];
            $valoresClientes = [0]; 
        }

        // ==========================================
        // 📊 GRÁFICO 2: Vendas Efetuadas por Item no Último Mês
        // ==========================================
        
        // Filtramos os pedidos concluídos nos últimos 30 dias
        $trintaDiasAtras = new \MongoDB\BSON\UTCDateTime((time() - (30 * 24 * 60 * 60)) * 1000);
        
        // Buscamos os pedidos desse período
        // Ajuste o nome da coleção se for 'pedidos', 'vendas', etc.
        $pedidos = $db->orders->find([
            'created_at' => ['$gte' => $trintaDiasAtras],
            'status' => 'concluido' // garante que contamos apenas vendas de fato concluídas
        ]);

        $vendasPorItem = [];

        foreach ($pedidos as $pedido) {
            $pedidoArray = is_object($pedido) ? (array) $pedido : $pedido;
            
            // Verifica se o pedido contém itens
            if (isset($pedidoArray['items']) && (is_array($pedidoArray['items']) || is_object($pedidoArray['items']))) {
                foreach ($pedidoArray['items'] as $item) {
                    $itemArray = (array) $item;
                    $nomeProduto = $itemArray['name'] ?? $itemArray['title'] ?? 'Produto sem Nome';
                    $quantidade = (int)($itemArray['quantity'] ?? 1);

                    if (!isset($vendasPorItem[$nomeProduto])) {
                        $vendasPorItem[$nomeProduto] = 0;
                    }
                    $vendasPorItem[$nomeProduto] += $quantidade;
                }
            }
        }

        // Ordenamos os produtos do mais vendido para o menos vendido
        arsort($vendasPorItem);

        // Limitamos para exibir no máximo os 7 produtos mais vendidos para não poluir o layout
        $vendasPorItemLimitado = array_slice($vendasPorItem, 0, 7, true);

        $labelsProdutos = array_keys($vendasPorItemLimitado);
        $valoresProdutos = array_values($vendasPorItemLimitado);

        // Fallback de dados de teste caso você não tenha vendas cadastradas nos últimos 30 dias
        if (empty($labelsProdutos)) {
            $labelsProdutos = ["Luva de Beisebol Pro", "Taco de Ipê Premium", "Bola NPB Oficial", "Boné MatsuStore"];
            $valoresProdutos = [15, 12, 8, 5]; // Exemplo ilustrativo
        }

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function listUsers()
    {
        $db = \App\Config\Database::getDatabase();

        // Busca todos os usuários ordenados por nome
        $cursor = $db->users->find([], ['sort' => ['name' => 1]]);
        
        // Garante a conversão correta dos documentos para array/objetos fáceis de ler no PHP
        $users = [];
        foreach ($cursor as $doc) {
            $users[] = (array) $doc;
        }

        require_once __DIR__ . '/../Views/admin/users_list.php';
    }

    public function saveUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/usuarios');
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $role     = $_POST['role'] ?? 'client';
        $isadmin  = isset($_POST['isadmin']) && $_POST['isadmin'] === '1';

        if (empty($name) || !$email || empty($password)) {
            $_SESSION['error'] = "Preencha todos os campos obrigatórios corretamente.";
            header('Location: /admin/usuarios');
            exit;
        }

        try {
            $db = \App\Config\Database::getDatabase();

            // 🔍 Validação: Evita e-mails duplicados no MongoDB
            $existingUser = $db->users->findOne(['email' => $email]);
            if ($existingUser) {
                $_SESSION['error'] = "Este e-mail já está cadastrado no sistema.";
                header('Location: /admin/usuarios');
                exit;
            }

            // 🔐 Criptografa a senha com Bcrypt antes de salvar
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $db->users->insertOne([
                'name'       => $name,
                'email'      => $email,
                'password'   => $hashedPassword,
                'role'       => $role,
                'isadmin'    => $isadmin,
                'isativo'    => true,
                'created_at' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
            ]);

            $_SESSION['success'] = "Usuário cadastrado com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao cadastrar usuário: " . $e->getMessage();
        }

        header('Location: /admin/usuarios');
        exit;
    }

    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/usuarios');
            exit;
        }

        $id       = $_POST['id'] ?? '';
        $name     = trim($_POST['name'] ?? '');
        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $role     = $_POST['role'] ?? 'client';
        $isadmin  = isset($_POST['isadmin']) && $_POST['isadmin'] === '1';
        $password = $_POST['password'] ?? '';

        if (empty($id) || empty($name) || !$email) {
            $_SESSION['error'] = "Preencha os campos obrigatórios corretamente.";
            header('Location: /admin/usuarios');
            exit;
        }

        try {
            $db = \App\Config\Database::getDatabase();

            // Monta os dados básicos de atualização
            $setData = [
                'name'    => $name,
                'email'   => $email,
                'role'    => $role,
                'isadmin' => $isadmin
            ];

            // Se o administrador digitou uma nova senha, criptografa e atualiza
            if (!empty($password)) {
                $setData['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            $db->users->updateOne(
                ['_id' => new \MongoDB\BSON\ObjectId($id)],
                ['$set' => $setData]
            );

            $_SESSION['success'] = "Usuário atualizado com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao atualizar usuário: " . $e->getMessage();
        }

        header('Location: /admin/usuarios');
        exit;
    }

    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/usuarios');
            exit;
        }

        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            $_SESSION['error'] = "Usuário inválido.";
            header('Location: /admin/usuarios');
            exit;
        }

        // 🛡️ TRAVA DE SEGURANÇA MÁXIMA: Evita auto-exclusão
        if ($id === $_SESSION['user']['id']) {
            $_SESSION['error'] = "Você não pode excluir sua própria conta de administrador!";
            header('Location: /admin/usuarios');
            exit;
        }

        try {
            $db = \App\Config\Database::getDatabase();
            
            $db->users->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

            $_SESSION['success'] = "Usuário removido com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao remover usuário: " . $e->getMessage();
        }

        header('Location: /admin/usuarios');
        exit;
    }

    public function listProducts()
    {
        $db = \App\Config\Database::getDatabase();

        // 1. Busca todos os produtos ordenados pelo nome
        $cursor = $db->products->find([], ['sort' => ['name' => 1]]);
        
        $products = [];
        $existingCategories = [];
        
        foreach ($cursor as $doc) {
            $arr = (array) $doc;
            $products[] = $arr;
            
            // Coleta categorias existentes para dar sugestões no formulário
            if (!empty($arr['category'])) {
                $existingCategories[$arr['category']] = true;
            }
        }

        // 2. Escaneia dinamicamente a pasta de imagens do projeto
        $imgPath = __DIR__ . '/../../public/assets/img';
        $availableImages = [];
        
        if (is_dir($imgPath)) {
            // Lê o diretório e filtra apenas arquivos de imagem comuns
            $files = scandir($imgPath);
            foreach ($files as $file) {
                if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $availableImages[] = $file;
                }
            }
        }

        // Transforma as chaves em um array simples de categorias únicas
        $categories = array_keys($existingCategories);

        // Envia as variáveis para a View
        require_once __DIR__ . '/../Views/admin/products_list.php';
    }

    public function saveProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/produtos');
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? 'Geral');
        $price    = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $stock    = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
        $image    = $_POST['image'] ?? '';

        if (empty($name) || $price === false || $stock === false) {
            $_SESSION['error'] = "Preencha todos os campos obrigatórios corretamente.";
            header('Location: /admin/produtos');
            exit;
        }

        try {
            $db = \App\Config\Database::getDatabase();

            $db->products->insertOne([
                'name'       => $name,
                'category'   => $category,
                'price'      => (float)$price,
                'stock'      => (int)$stock,
                'image'      => $image,
                'created_at' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
            ]);

            $_SESSION['success'] = "Produto cadastrado com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao cadastrar produto: " . $e->getMessage();
        }

        header('Location: /admin/produtos');
        exit;
    }

    public function updateProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/produtos');
            exit;
        }

        $id       = $_POST['id'] ?? '';
        $name     = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? 'Geral');
        $price    = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $stock    = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
        $image    = $_POST['image'] ?? '';

        if (empty($id) || empty($name) || $price === false || $stock === false) {
            $_SESSION['error'] = "Preencha todos os campos obrigatórios corretamente.";
            header('Location: /admin/produtos');
            exit;
        }

        try {
            $db = \App\Config\Database::getDatabase();

            $db->products->updateOne(
                ['_id' => new \MongoDB\BSON\ObjectId($id)],
                ['$set' => [
                    'name'     => $name,
                    'category' => $category,
                    'price'    => (float)$price,
                    'stock'    => (int)$stock,
                    'image'    => $image
                ]]
            );

            $_SESSION['success'] = "Produto atualizado com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao atualizar produto: " . $e->getMessage();
        }

        header('Location: /admin/produtos');
        exit;
    }

    public function deleteProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/produtos');
            exit;
        }

        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            $_SESSION['error'] = "ID do produto não fornecido.";
            header('Location: /admin/produtos');
            exit;
        }

        try {
            $db = \App\Config\Database::getDatabase();

            $db->products->deleteOne([
                '_id' => new \MongoDB\BSON\ObjectId($id)
            ]);

            $_SESSION['success'] = "Produto excluído com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao excluir produto: " . $e->getMessage();
        }

        header('Location: /admin/produtos');
        exit;
    }

    public function toggleUserStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/usuarios');
            exit;
        }

        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            $_SESSION['error'] = "Usuário inválido.";
            header('Location: /admin/usuarios');
            exit;
        }

        // 🛡️ Segurança: Evita que o admin desative a si mesmo
        if ($id === $_SESSION['user']['id']) {
            $_SESSION['error'] = "Você não pode desativar sua própria conta de administrador!";
            header('Location: /admin/usuarios');
            exit;
        }

        try {
            $db = \App\Config\Database::getDatabase();
            
            // Busca o usuário atual para checar o status
            $user = $db->users->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
            
            if (!$user) {
                $_SESSION['error'] = "Usuário não encontrado.";
                header('Location: /admin/usuarios');
                exit;
            }

            // Se o campo 'isativo' não existir (usuários legados), consideramos true por padrão
            $statusAtual = isset($user['isativo']) ? (bool)$user['isativo'] : true;
            $novoStatus = !$statusAtual;

            $db->users->updateOne(
                ['_id' => new \MongoDB\BSON\ObjectId($id)],
                ['$set' => ['isativo' => $novoStatus]]
            );

            $_SESSION['success'] = "Status do usuário atualizado com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao alterar status do usuário: " . $e->getMessage();
        }

        header('Location: /admin/usuarios');
        exit;
    }
}