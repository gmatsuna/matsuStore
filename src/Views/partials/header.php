<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>matsuStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="text-2xl font-black tracking-wider text-indigo-600">
                matsu<span class="text-gray-900">Store</span>
            </div>
            
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <input type="text" placeholder="Buscar produtos..." class="w-full h-10 px-4 rounded-lg bg-gray-100 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 text-sm transition">
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium cursor-pointer hover:text-indigo-600 transition">Minha Conta</span>
                <div class="relative cursor-pointer">
                    <span class="text-xl">🛒</span>
                    <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">0</span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex flex-1 py-6 gap-6">