<?php
header("Content-Type: application/xml; charset=utf-8");
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/Product.php';

// Função para decodificar totalmente entidades HTML que possam estar duplamente codificadas no banco de dados
function fully_decode_entities($string) {
    if (empty($string)) return '';
    $decoded = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    while ($decoded !== $string) {
        $string = $decoded;
        $decoded = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    }
    return $decoded;
}

$productModel = new Product($conn);
$products = $productModel->getAllWithouPagnation();

// Detecta o protocolo e host dinamicamente para gerar URLs absolutas (essencial para a Meta)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $domain;
if (strpos($_SERVER['REQUEST_URI'], '/baoba_ervas_web') !== false) {
    $base_url .= '/baoba_ervas_web';
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\n";
echo '  <channel>' . "\n";
echo '    <title>Baobá Brasil - Ervas e Cereais</title>' . "\n";
echo '    <link>' . $base_url . '</link>' . "\n";
echo '    <description>Catálogo de produtos naturais - Ervas, chás, temperos, castanhas e muito mais.</description>' . "\n";

foreach ($products as $p) {
    if (!$p['active']) {
        continue;
    }

    $price = $p['price'];
    if ($p['discount'] > 0) {
        $price = $p['price'] * $p['discount'];
    }

    // Limpa a descrição removendo tags HTML e convertendo as entidades para texto legível
    $clean_desc = strip_tags(fully_decode_entities($p['description']));
    $clean_desc = trim(preg_replace('/\s+/', ' ', $clean_desc));
    
    $p_name = fully_decode_entities($p['name']);
    if (empty($clean_desc)) {
        $clean_desc = $p_name;
    }

    // Processa a imagem convertendo o caminho relativo em absoluto
    $array_path_image = explode("/", $p['path_image']);
    $path_image = "";
    foreach ($array_path_image as $key => $value) {
        if ($key != 0) {
            $path_image .= "/" . $value;
        }
    }
    $image_url = $base_url . $path_image;
    $product_url = $base_url . '/index.php?page=product&slogan=' . urlencode($p['slogan']);

    $stock = $p['stock_quantity'] > 0 ? 'in stock' : 'out of stock';

    echo '    <item>' . "\n";
    echo '      <g:id>' . $p['product_id'] . '</g:id>' . "\n";
    echo '      <g:title>' . htmlspecialchars($p_name, ENT_XML1) . '</g:title>' . "\n";
    echo '      <g:description>' . htmlspecialchars($clean_desc, ENT_XML1) . '</g:description>' . "\n";
    echo '      <g:link>' . htmlspecialchars($product_url, ENT_XML1) . '</g:link>' . "\n";
    echo '      <g:image_link>' . htmlspecialchars($image_url, ENT_XML1) . '</g:image_link>' . "\n";
    echo '      <g:availability>' . $stock . '</g:availability>' . "\n";
    echo '      <g:quantity_to_sell_on_facebook>' . intval($p['stock_quantity']) . '</g:quantity_to_sell_on_facebook>' . "\n";
    echo '      <g:price>' . number_format($price, 2, '.', '') . ' BRL</g:price>' . "\n";
    echo '      <g:brand>Baobá Brasil</g:brand>' . "\n";
    echo '      <g:condition>new</g:condition>' . "\n";
    echo '    </item>' . "\n";
}

echo '  </channel>' . "\n";
echo '</rss>' . "\n";
