<?php

namespace App\Controllers;

use App\Config\Database;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Garante que o usuário está logado
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Garante que o carrinho possui itens
        if (empty($_SESSION['cart'])) {
            header('Location: /carrinho');
            exit;
        }

        // Configura a SDK com sua chave de testes
        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        // Calcula o valor total do carrinho em CENTAVOS (R$ 10,00 vira 1000 centavos)
        $totalCentavos = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalCentavos += ($item['price'] * $item['quantity']) * 100;
        }

        try {
            // Cria a intenção de pagamento no Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalCentavos,
                'currency' => 'brl',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            // Guardamos temporariamente o ID do pagamento na sessão
            $_SESSION['payment_intent_id'] = $paymentIntent->id;

            // Chave temporária que o JS do Stripe usará para renderizar o form de forma segura
            $clientSecret = $paymentIntent->client_secret;

            require_once __DIR__ . '/../Views/checkout.php';

        } catch (\Exception $e) {
            echo "Erro ao processar pagamento com o Stripe: " . $e->getMessage();
        }
    }

    public function sucesso()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = Database::getDatabase();

        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        
        // O Stripe envia o ID do pagamento na URL de redirecionamento como "payment_intent"
        $paymentIntentId = $_GET['payment_intent'] ?? $_SESSION['payment_intent_id'] ?? null;

        if (!$paymentIntentId) {
            header('Location: /');
            exit;
        }

        try {
            // Confirmamos o status real direto na API do Stripe
            $intent = PaymentIntent::retrieve($paymentIntentId);

            // Se o pagamento foi efetuado com sucesso pelo Stripe!
            if ($intent->status === 'succeeded') {
                
                // Prepara a estrutura do pedido para salvar no MongoDB
                $novoPedido = [
                    'user_id' => $_SESSION['user']['id'] ?? $_SESSION['user']['_id'] ?? 'Guest',
                    'items' => $_SESSION['cart'],
                    'total' => $intent->amount / 100, 
                    'payment_method' => 'stripe_card',
                    'status' => 'concluido', 
                    'created_at' => new \MongoDB\BSON\UTCDateTime(),
                    'stripe_payment_id' => $paymentIntentId
                ];

                // 1. Salva na coleção 'orders'
                $db->orders->insertOne($novoPedido);

                // 2. Loop para subtrair a quantidade vendida do estoque
                foreach ($_SESSION['cart'] as $item) {
                    if (isset($item['id'])) {
                        $productId = new \MongoDB\BSON\ObjectId($item['id']);
                        $quantidadeVendida = (int)$item['quantity'];

                        $db->products->updateOne(
                            ['_id' => $productId],
                            ['$inc' => ['stock' => -$quantidadeVendida]]
                        );
                    }
                }

                // 3. Limpa o carrinho
                $_SESSION['cart'] = [];
                
                // 4. Carrega a view de sucesso moderna e elegante!
                require_once __DIR__ . '/../Views/checkout-success.php';

            } else {
                echo "O pagamento não foi aprovado pelo Stripe. Status: " . $intent->status;
            }

        } catch (\Exception $e) {
            echo "Erro ao processar confirmação de pagamento: " . $e->getMessage();
        }
    }
}