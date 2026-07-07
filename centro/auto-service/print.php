<?php
if (!isset($_GET['number'])) {
    header("Location: index.php");
    exit;
}
$number = $_GET['number'];
$type_label = (strpos($number, 'P') === 0) ? 'PRIORITÁRIO' : 'COMUM';
date_default_timezone_set('America/Cuiaba');
$date = date('d/m/Y H:i:s');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Imprimindo Senha - <?= htmlspecialchars($number) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .receipt-container {
            max-width: 280px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 15px;
        }
        .header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .subheader {
            font-size: 12px;
            margin-bottom: 15px;
        }
        .ticket-number {
            font-size: 42px;
            font-weight: bold;
            margin: 10px 0;
        }
        .ticket-type {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            margin-bottom: 15px;
        }
        .footer {
            font-size: 10px;
            margin-top: 15px;
        }
        
        /* Oculta tudo na tela após carregar e foca apenas no diálogo de impressão */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .receipt-container {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">Baobá Ervas</div>
        <div class="subheader">Filial Centro</div>
        
        <div>SUA SENHA É:</div>
        <div class="ticket-number"><?= htmlspecialchars($number) ?></div>
        
        <div class="ticket-type">ATENDIMENTO <?= $type_label ?></div>
        
        <div><?= $date ?></div>
        
        <div class="footer">
            Aguarde ser chamado no painel.<br>
            Obrigado pela preferência!
        </div>
    </div>

    <script>
        function redirecionar() {
            window.location.href = 'index.php';
        }

        window.onload = function () {
            // Registra o evento para navegadores que suportam
            window.onafterprint = redirecionar;
            
            setTimeout(function () {
                window.print();
                // Redirecionamento de contingência imediata logo após fechar o diálogo
                setTimeout(redirecionar, 200);
            }, 100);
        };
    </script>
</body>
</html>
