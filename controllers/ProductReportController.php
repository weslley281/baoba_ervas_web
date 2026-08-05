<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Restrição de acesso apenas para administradores logados
if (!isset($_SESSION["user_id"]) || $_SESSION['user_type'] != "admin") {
    header("Location: ../index.php?page=login");
    exit;
}

$productModel = new Product($conn);
$categoryModel = new Category($conn);

$products = $productModel->getAllWithouPagnation();

// Função auxiliar para carregar imagens locais em Base64 para exibição direta no PDF
function get_base64_image($path_image) {
    if (empty($path_image)) {
        return '';
    }
    
    // Converte o caminho do banco (que pode ter "../" ou "views/") para um caminho real
    $array_path_image = explode("/", $path_image);
    $relative_path = "";
    foreach ($array_path_image as $key => $value) {
        if ($key != 0) {
            $relative_path .= "/" . $value;
        }
    }
    
    $file_path = __DIR__ . '/..' . $relative_path;
    if (file_exists($file_path)) {
        $type = pathinfo($file_path, PATHINFO_EXTENSION);
        $data = file_get_contents($file_path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return '';
}

// Data e Hora de geração do relatório
date_default_timezone_set('America/Cuiaba');
$current_date = date('d/m/Y H:i:s');
$total_products = count($products);

// Montagem das linhas da tabela
$rows_html = '';
foreach ($products as $p) {
    $category_name = $categoryModel->getNameById($p['category_id']);
    
    // Tratamento de Imagem
    $base64_img = get_base64_image($p['path_image']);
    $img_tag = !empty($base64_img) 
        ? '<img class="product-img" src="' . $base64_img . '" alt="Img">' 
        : '<span style="color: #ccc; font-size: 8px;">Sem foto</span>';

    // Formatação de Preço com desconto se houver
    $price = $p['price'];
    if ($p['discount'] > 0) {
        $price = $p['price'] * $p['discount'];
    }
    $price_formatted = 'R$ ' . number_format($price, 2, ',', '.');
    if ($p['discount'] > 0) {
        $price_formatted .= '<br><span style="text-decoration: line-through; color: #999; font-size: 8px;">R$ ' . number_format($p['price'], 2, ',', '.') . '</span>';
    }

    // Badge de status ativo/inativo
    $status_badge = $p['active'] 
        ? '<span class="badge badge-active">Ativo</span>' 
        : '<span class="badge badge-inactive">Inativo</span>';

    $rows_html .= '
    <tr>
        <td style="text-align: center;">' . $p['product_id'] . '</td>
        <td>' . htmlspecialchars($p['reference']) . '</td>
        <td style="text-align: center;">' . $img_tag . '</td>
        <td style="font-weight: bold; color: #1a1a1a;">' . htmlspecialchars($p['name']) . '</td>
        <td>' . htmlspecialchars($category_name) . '</td>
        <td>' . $price_formatted . '</td>
        <td style="text-align: center;">' . $p['stock_quantity'] . '</td>
        <td style="text-align: center;">' . $status_badge . '</td>
    </tr>';
}

// Template HTML Completo para o Dompdf
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 40px 30px;
        }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 10px;
            line-height: 1.3;
        }
        .header {
            border-bottom: 2px solid #198754;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .logo-title {
            float: left;
        }
        .logo-title h1 {
            color: #198754;
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .logo-title p {
            margin: 2px 0 0 0;
            color: #6c757d;
            font-size: 11px;
            font-weight: bold;
        }
        .meta-info {
            float: right;
            text-align: right;
            color: #6c757d;
            font-size: 9px;
            line-height: 1.4;
        }
        .clearfix {
            clear: both;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .table th {
            background-color: #198754;
            color: #fff;
            text-align: left;
            padding: 6px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            border: 1px solid #198754;
        }
        .table td {
            padding: 6px;
            border-bottom: 1px solid #dee2e6;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            vertical-align: middle;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .product-img {
            width: 28px;
            height: 28px;
            border-radius: 4px;
        }
        .badge {
            display: inline-block;
            padding: 2px 4px;
            font-size: 7px;
            font-weight: bold;
            color: #fff;
            border-radius: 3px;
            text-align: center;
        }
        .badge-active {
            background-color: #198754;
        }
        .badge-inactive {
            background-color: #dc3545;
        }
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            color: #adb5bd;
            font-size: 8px;
            border-top: 1px solid #dee2e6;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <h1>Baobá Brasil</h1>
            <p>Relatório Geral de Produtos Cadastrados</p>
        </div>
        <div class="meta-info">
            <strong>Gerado em:</strong> ' . $current_date . '<br>
            <strong>Total de Produtos:</strong> ' . $total_products . '
        </div>
        <div class="clearfix"></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">ID</th>
                <th style="width: 10%;">Referência</th>
                <th style="width: 8%; text-align: center;">Foto</th>
                <th style="width: 37%;">Nome</th>
                <th style="width: 15%;">Categoria</th>
                <th style="width: 12%;">Preço Venda</th>
                <th style="width: 8%; text-align: center;">Estoque</th>
                <th style="width: 7%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            ' . $rows_html . '
        </tbody>
    </table>

    <div class="footer">
        Baobá Brasil - Ervas &amp; Cereais &copy; ' . date('Y') . ' - Todos os direitos reservados.
    </div>
</body>
</html>';

// Inicializa o Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false); // Usamos Base64, por segurança mantemos desativado
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Força o download do PDF ou abre direto no navegador (Attachment => 0 abre no navegador)
$dompdf->stream("Relatorio_Produtos_Baoba_Brasil.pdf", array("Attachment" => 0));
exit;
