<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>matsuStore</title>
</head>
<body>
    <h1>Produtos</h1>

    <?php foreach ($products as $product): ?>
        <div>
            <!-- Aqui você escreve o h2 com o nome do produto -->
            <h2><?= $product->name ?></h2>

            <!-- Aqui, escreva um <p> mostrando $product->description -->
            <p><?= $product->description ?></p>

            <!-- Aqui, escreva um <p> mostrando $product->price -->
            <p>R$ <?= number_format($product->price, 2, ',', '.') ?></p>
            
        </div>
    <?php endforeach; ?>

</body>
</html>
