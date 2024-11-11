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
    if (isset($_GET["slogan"])) {
        $description = $product->getDescription($_GET["slogan"]);
        $description = htmlspecialchars_decode($description, ENT_QUOTES);
        // Limita o título a 20 caracteres e adiciona reticências se for maior que 20
        $description = strlen($description) > 20 ? substr($description, 0, 20) . '...' : $description;
    ?>
        <meta name="description" content="<?= $description; ?>">
    <?php
    } else {
    ?>
        <meta name="description" content="Somos uma loja de produtos naturais, trabalhamos com venda de ervas, emcapsulados, chás, ervas, temperos, açucares, castanhas, farinaceos e frutas secas.">
    <?php
    }
    ?>

    <meta http-equiv="refresh" content="3600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">

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