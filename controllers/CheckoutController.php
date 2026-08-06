<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/stores.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/SaleItem.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: ../index.php?page=cart");
    exit;
}

$customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$preferred_store = isset($_POST['preferred_store']) ? trim($_POST['preferred_store']) : '';
$payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

// Validação dos campos obrigatórios
if (empty($customer_name) || empty($phone) || empty($preferred_store) || empty($payment_method)) {
    header("Location: ../index.php?page=cart&error=missing_fields");
    exit;
}

if (!isset(STORES[$preferred_store])) {
    header("Location: ../index.php?page=cart&error=invalid_store");
    exit;
}

// Calcula o valor total
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['amount'];
}

// Gera código único do pedido (#BB + 5 caracteres hex/alfa)
$ticket_code = '#BB-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));

$customer_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

$saleModel = new Sale($conn);
$saleItemModel = new SaleItem($conn);

$sale_data = [
    "customer_id" => $customer_id,
    "ticket_code" => $ticket_code,
    "customer_name" => $customer_name,
    "phone" => $phone,
    "preferred_store" => $preferred_store,
    "payment_method" => $payment_method,
    "total_price" => $total_price,
    "delivery_type" => "retirada", // Preparado para delivery no futuro
    "delivery_address" => null,
    "situation" => "Pendente"
];

// Salva o pedido no banco de dados
$sale_id = $saleModel->create($sale_data);

if ($sale_id) {
    // Salva os itens do pedido no banco de dados
    foreach ($_SESSION['cart'] as $id => $item) {
        $item_data = [
            "sale_id" => $sale_id,
            "product_id" => $id,
            "price" => $item['price'],
            "quantity" => $item['amount'],
            "name" => $item['name']
        ];
        $saleItemModel->create($item_data);
    }

    // Tradução amigável do método de pagamento
    $payments_display = [
        'pix' => 'Pix',
        'cartao' => 'Cartão na Retirada',
        'dinheiro' => 'Dinheiro na Retirada'
    ];
    $payment_name = $payments_display[$payment_method] ?? $payment_method;
    $store_name = STORES[$preferred_store]['name'];

    // Monta a mensagem formatada para o WhatsApp
    $whatsappText = "📝 *NOVO PEDIDO - BAOBÁ BRASIL*\n";
    $whatsappText .= "🆔 *Pedido:* " . $ticket_code . "\n";
    $whatsappText .= "👤 *Cliente:* " . $customer_name . "\n";
    $whatsappText .= "📞 *WhatsApp:* " . $phone . "\n";
    $whatsappText .= "📍 *Filial para Retirada:* " . $store_name . "\n";
    $whatsappText .= "💳 *Pagamento:* " . $payment_name . "\n\n";
    $whatsappText .= "--------------------------------------\n\n";
    $whatsappText .= "*PRODUTOS:*\n";
    
    foreach ($_SESSION['cart'] as $item) {
        $whatsappText .= "• _" . $item['name'] . "_ | Qtd: " . $item['amount'] . " | R$ " . number_format($item['price'], 2, ',', '.') . "\n";
    }
    
    $whatsappText .= "\n💰 *TOTAL:* R$ " . number_format($total_price, 2, ',', '.') . "\n\n";
    $whatsappText .= "_Mande esta mensagem para finalizar o pedido e retirar na loja física!_";

    $store_phone = STORES[$preferred_store]['phone'];
    $waLink = "https://wa.me/" . $store_phone . "?text=" . urlencode($whatsappText);

    // Salva a URL na sessão para abrir no sucesso e limpa o carrinho
    $_SESSION['whatsapp_order_url'] = $waLink;
    $_SESSION['cart'] = [];

    header("Location: ../index.php?page=cart&action=success&ticket=" . urlencode($ticket_code) . "&store=" . urlencode($preferred_store));
    exit;
} else {
    header("Location: ../index.php?page=cart&error=db_fail");
    exit;
}
