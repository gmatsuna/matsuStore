<?php

namespace App\Controllers;

use App\Models\Product;

class HomeController
{
    public function index(): void
    {
        $products = Product::all();
        require __DIR__ . '/../Views/home.php';
    }
}
