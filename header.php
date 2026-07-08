<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="google-site-verification" content="bFcShvg07pMBqFUxjHUdnNjkfMf3-lwSWlnvcN4AuM8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Weslley Henrique Vieira Ferraz" />
    <meta name="owner" content="Comercio de utilidades Baoba LTDA - ME" />
    <meta name="copyright" content="Weslley Henrique Vieira Ferraz" />
    <meta name="keywords" content="erva, tempero, produto natural, cha, castanha, farinaceo, farinha, doce, fruta seca">
    <?php
    // Detecta o protocolo e host dinamicamente para gerar URLs absolutas (essencial para compartilhamento)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $base_url = $protocol . $domain;
    if (strpos($_SERVER['REQUEST_URI'], '/baoba_ervas_web') !== false) {
        $base_url .= '/baoba_ervas_web';
    }

    if ($page == "product" && isset($_GET["slogan"])) {
        $description = html_entity_decode(strip_tags($product->getDescription($_GET["slogan"])), ENT_QUOTES, 'UTF-8');
        $description = strlen($description) > 150 ? substr($description, 0, 150) . '...' : $description;
    ?>
        <meta name="description" content="<?= htmlspecialchars($description); ?>">
    <?php
    } else {
    ?>
        <meta name="description" content="Somos uma loja de produtos naturais, trabalhamos com venda de ervas, encapsulados, chás, ervas, temperos, açúcares, castanhas, farináceos e frutas secas.">
    <?php
    }
    ?>

    <meta http-equiv="refresh" content="3600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">


    <?php
    if ($page == "product" && isset($_GET["slogan"])) {
        $sup = $product->getBySlogan($_GET["slogan"]);
        $array_path_image = explode("/", $sup['path_image']);
        $path_image = "";

        foreach ($array_path_image as $key => $value) {
            if ($key != 0) {
                $path_image = $path_image . "/" . $value;
            }
        }
        $abs_image_url = $base_url . $path_image;
        $abs_product_url = $base_url . '/index.php?page=product&slogan=' . $sup['slogan'];

        // Remove tags HTML da descrição e limita tamanho
        $clean_desc = html_entity_decode(strip_tags($sup["description"]), ENT_QUOTES, 'UTF-8');
        $clean_desc = strlen($clean_desc) > 150 ? substr($clean_desc, 0, 150) . '...' : $clean_desc;
    ?>
        <!-- Open Graph para compartilhamento -->
        <meta property="og:title" content="<?= htmlspecialchars($sup['name']); ?>">
        <meta property="og:description" content="<?= htmlspecialchars($clean_desc); ?>">
        <meta property="og:image" content="<?= $abs_image_url; ?>">
        <meta property="og:url" content="<?= $abs_product_url; ?>">
        <meta property="og:type" content="website">

        <!-- Informações adicionais para produtos no Open Graph -->
        <meta property="product:price:amount" content="<?= number_format($sup['price'], 2, ',', '.') ?>">
        <meta property="product:price:currency" content="BRL">
        <meta property="product:availability" content="in stock"> <!-- 'out of stock' se estiver indisponível -->
        <meta property="product:brand" content="Baobá Brasil">

        <!-- Twitter Cards -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?= htmlspecialchars($sup['name']); ?>">
        <meta name="twitter:description" content="<?= htmlspecialchars($clean_desc); ?>">
        <meta name="twitter:image" content="<?= $abs_image_url; ?>">
    <?php
    } else {
        $abs_logo_url = $base_url . '/images/logo.png';
        $abs_site_url = $base_url . '/index.php';
        $site_desc = "Somos uma loja de produtos naturais, trabalhamos com venda de ervas, encapsulados, chás, ervas, temperos, açúcares, castanhas, farináceos e frutas secas.";
    ?>
        <!-- Metatags de Open Graph -->
        <meta property="og:title" content="<?= $page_title; ?>">
        <meta property="og:description" content="<?= $site_desc; ?>">
        <meta property="og:image" content="<?= $abs_logo_url; ?>">
        <meta property="og:url" content="<?= $abs_site_url; ?>">
        <meta property="og:type" content="website">
    <?php
    }
    ?>

    <title><?php echo $page_title; ?></title>

    <link rel="shortcut icon" href="./images/icone.png" type="image/x-icon">

    <link rel="stylesheet" href="./libs/bootstrap/bootstrap.css">
    <link href="./css/styles.css" rel="stylesheet" />

    <link href="./libs/fontawesome-free-6.5.2-web/css/fontawesome.css" rel="stylesheet" />
    <link href="./libs/fontawesome-free-6.5.2-web/css/brands.css" rel="stylesheet" />
    <link href="./libs/fontawesome-free-6.5.2-web/css/solid.css" rel="stylesheet" />
    <link href="./libs/alertifyjs/css/alertify.css" rel="stylesheet" />

    <link rel="stylesheet" href="./libs/DataTables/datatables.css">
    <link rel="icon" href="./images/icone.png" type="image/jpg">
    <link rel="stylesheet" href="./libs/select2/css/select2.css">

    <!-- Link para o Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>