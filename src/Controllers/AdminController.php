<?php

namespace App\Controllers;

use App\Config\Database;
use MongoDB\BSON\ObjectId;

class AdminController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'employee') {
            $_SESSION['error'] = "Acesso restrito! Esta área é exclusiva para funcionários cadastrados.";
            header('Location: /login');
            exit;
        }
    }

    public function index()
    {
        $db = Database::getDatabase();
        
        // Busca todos os produtos para listar na tabela do painel
        $cursor = $db->products->find([], ['sort' => ['name' => 1]]);
        $products = iterator_to_array($cursor);

        require_once __DIR__ . '/../Views/admin.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $category = trim($_POST['category'] ?? 'Geral');
        $image = trim($_POST['image'] ?? 'placeholder.png'); // nome do arquivo na pasta assets

        if (empty($name) || $price <= 0 || $stock < 0) {
            $_SESSION['error'] = "Preencha os campos de produto corretamente.";
            header('Location: /admin');
            exit;
        }

        try {
            $db = Database::getDatabase();
            $db->products->insertOne([
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'category' => $category,
                'image' => $image,
                'created_at' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
            ]);

            $_SESSION['success'] = "Produto cadastrado com sucesso!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao cadastrar: " . $e->getMessage();
        }

        header('Location: /admin');
        exit;
    }

    public function updateStock()
    {
        $productId = $_POST['product_id'] ?? '';
        $newStock = (int)($_POST['stock'] ?? 0);

        if (empty($productId) || $newStock < 0) {
            header('Location: /admin');
            exit;
        }

        try {
            $db = Database::getDatabase();
            $db->products->updateOne(
                ['_id' => new ObjectId($productId)],
                ['$set' => ['stock' => $newStock]]
            );
            $_SESSION['success'] = "Estoque atualizado!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erro ao atualizar estoque.";
        }

        header('Location: /admin');
        exit;
    }
}