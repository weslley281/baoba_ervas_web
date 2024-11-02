<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Weslley Henrique Vieira Ferraz" />
    <meta name="owner" content="Comercio de utilidades Baoba LTDA - ME" />
    <meta name="copyright" content="Weslley Henrique Vieira Ferraz" />
    <meta name="keywords" content="erva, tempero, produto natural, cha, castanha, farinaceo, farinha, doce, fruta seca">
    <?php
    if (isset($_GET["slogan"])) {
        $description = $product->getDescription($_GET["slogan"]);
    ?>
        <meta name="description" content="<?= htmlspecialchars_decode($description, ENT_QUOTES) ?>">
    <?php
    } else {
    ?>
        <meta name="description" content="Somos uma loja de produtos naturais, trabalhamos com venda de ervas, emcapsulados, chás, ervas, temperos, açucares, castanhas, farinaceos e frutas secas.">
    <?php
    }
    ?>

    <meta http-equiv="refresh" content="3600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo $page_title; ?></title>

    <link rel="shortcut icon" href="./images/icone.png" type="image/x-icon">

    <link rel="stylesheet" href="./libs/bootstrap/bootstrap.css">
    <link href="./css/styles.css" rel="stylesheet" />

    <link href="./libs/fontawesome-free-6.5.2-web/css/fontawesome.css" rel="stylesheet" />
    <link href="./libs/fontawesome-free-6.5.2-web/css/brands.css" rel="stylesheet" />
    <link href="./libs/fontawesome-free-6.5.2-web/css/solid.css" rel="stylesheet" />
    <link href="./libs/alertifyjs/css/alertify.css" rel="stylesheet" />

    <link rel="stylesheet" href="./libs/DataTables/datatables.css">
    <link rel="icon" href="./images/logo-kenshydokan.png" type="image/jpg">
    <link rel="stylesheet" href="./libs/select2/css/select2.css">
</head>