<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';

// Restrição de acesso apenas para administradores logados
if (!isset($_SESSION["user_id"]) || $_SESSION['user_type'] != "admin") {
    header("Location: ../index.php?page=login");
    exit;
}

$productModel = new Product($conn);
$categoryModel = new Category($conn);

$products = $productModel->getAllWithouPagnation();

// Função auxiliar para conversão UTF-8 para ISO-8859-1 (FPDF Nativo)
function fix($txt) {
    return mb_convert_encoding($txt, 'ISO-8859-1', 'UTF-8');
}

// Extensão do FPDF para criar Cabeçalho e Rodapé Dinâmicos
class PDF extends FPDF
{
    function Header()
    {
        // Banner verde da Baobá Brasil
        $this->SetDrawColor(25, 135, 84); // Verde principal
        $this->SetLineWidth(0.8);
        $this->Line(10, 23, 200, 23);
        
        $this->SetXY(10, 10);
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(25, 135, 84);
        $this->Cell(120, 6, fix('BAOBÁ BRASIL'), 0, 0, 'L');
        
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(70, 3, fix('RELATÓRIO GERAL DE PRODUTOS'), 0, 1, 'R');
        
        $this->SetX(130);
        $this->SetFont('Arial', '', 7.5);
        $this->Cell(70, 3, fix('Gerado em: ' . date('d/m/Y H:i:s')), 0, 1, 'R');
        
        $this->Ln(6);
    }

    function Footer()
    {
        // Margem de 1.5 cm da borda inferior
        $this->SetY(-15);
        
        // Linha divisória cinza claro
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.2);
        $this->Line(10, 282, 200, 282);
        
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        
        // Assinatura da Loja à esquerda e numeração de página à direita
        $this->Cell(100, 10, fix('Baobá Brasil - Ervas & Cereais'), 0, 0, 'L');
        $this->Cell(90, 10, fix('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
}

// Inicializa o FPDF no formato A4 Retrato (P)
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);

// Cabeçalhos da Tabela
$pdf->SetFillColor(25, 135, 84); // Fundo Verde
$pdf->SetTextColor(255, 255, 255); // Texto Branco
$pdf->SetDrawColor(25, 135, 84); // Borda Verde
$pdf->SetLineWidth(0.2);
$pdf->SetFont('Arial', 'B', 8.5);

// Total de largura imprimível no A4 é 190mm (210mm - 20mm de margens)
$pdf->Cell(12, 7, 'ID', 1, 0, 'C', true);
$pdf->Cell(25, 7, fix('Referência'), 1, 0, 'L', true);
$pdf->Cell(68, 7, fix('Nome do Produto'), 1, 0, 'L', true);
$pdf->Cell(35, 7, fix('Categoria'), 1, 0, 'L', true);
$pdf->Cell(22, 7, fix('Preço Venda'), 1, 0, 'R', true);
$pdf->Cell(14, 7, fix('Estoque'), 1, 0, 'C', true);
$pdf->Cell(14, 7, fix('Status'), 1, 1, 'C', true); // Quebra de linha no último item

// Linhas da Tabela (Produtos)
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('Arial', '', 8);
$fill = false; // Alternar cores de fundo das linhas

foreach ($products as $p) {
    // Cor de fundo alternada
    if ($fill) {
        $pdf->SetFillColor(248, 249, 250); // Cinza muito claro
    } else {
        $pdf->SetFillColor(255, 255, 255); // Branco
    }
    $pdf->SetDrawColor(230, 230, 230); // Bordas cinzas sutis
    
    // Obtém o nome da categoria
    $category_name = $categoryModel->getNameById($p['category_id']);
    
    // Trunca nome do produto se for muito longo para caber na coluna (68mm)
    $prod_name = $p['name'];
    if (strlen($prod_name) > 38) {
        $prod_name = mb_substr($prod_name, 0, 35, 'UTF-8') . '...';
    }
    
    // Trunca nome da categoria para caber na coluna (35mm)
    $cat_name = $category_name;
    if (strlen($cat_name) > 20) {
        $cat_name = mb_substr($cat_name, 0, 17, 'UTF-8') . '...';
    }
    
    // Cálculo do preço final considerando desconto
    $price = $p['price'];
    if ($p['discount'] > 0) {
        $price = $p['price'] * $p['discount'];
    }
    $price_formatted = 'R$ ' . number_format($price, 2, ',', '.');
    
    $status_txt = $p['active'] ? 'Ativo' : 'Inativo';
    
    $pdf->Cell(12, 6, $p['product_id'], 1, 0, 'C', true);
    $pdf->Cell(25, 6, fix($p['reference']), 1, 0, 'L', true);
    $pdf->Cell(68, 6, fix($prod_name), 1, 0, 'L', true);
    $pdf->Cell(35, 6, fix($cat_name), 1, 0, 'L', true);
    $pdf->Cell(22, 6, fix($price_formatted), 1, 0, 'R', true);
    $pdf->Cell(14, 6, $p['stock_quantity'], 1, 0, 'C', true);
    
    // Destaque colorido para o status
    if ($p['active']) {
        $pdf->SetTextColor(25, 135, 84); // Verde para ativo
    } else {
        $pdf->SetTextColor(220, 53, 69); // Vermelho para inativo
    }
    
    $pdf->Cell(14, 6, fix($status_txt), 1, 1, 'C', true);
    
    $pdf->SetTextColor(50, 50, 50); // Reseta a cor padrão do texto para a próxima linha
    $fill = !$fill;
}

// Exibe o PDF diretamente no navegador (Inline)
$pdf->Output('I', 'Relatorio_Produtos_Baoba_Brasil.pdf');
exit;
