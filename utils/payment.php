<?php
// Chaves da API da Stripe
$public_key = 'sua_chave_publica';
$secret_key = 'sua_chave_secreta';

// URL da API da Stripe
$url = 'https://api.stripe.com/v1/charges';

// Dados do pagamento
$value = $_POST['value']; // Em centavos (ex: R$10,00)
$token = $_POST['stripeToken'];

// Cabeçalhos da solicitação
$headers = [
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Bearer ' . $secret_key,
];

// Dados da solicitação
$data = http_build_query([
    'amount' => $value,
    'currency' => 'BRL',
    'description' => 'Descrição do pagamento',
    'source' => $token,
]);

// Configurações da solicitação
$settings = [
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", $headers),
        'content' => $data,
    ],
];

// Contexto da solicitação
$context = stream_context_create($settings);

// Realizar a solicitação
$response = file_get_contents($url, false, $context);

// Verificar se a solicitação foi bem-sucedida
if ($response === false) {
    echo "Erro ao processar o pagamento.";
} else {
    $response_json = json_decode($response, true);
    if ($response_json && isset($response_json['id'])) {
        echo "Pagamento bem-sucedido. ID da cobrança: " . $response_json['id'];
    } else {
        echo "Erro ao processar o pagamento: " . $response_json['error']['message'];
    }
}
