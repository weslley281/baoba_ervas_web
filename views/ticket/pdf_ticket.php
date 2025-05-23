<?php
date_default_timezone_set('America/Cuiaba');

$ticket = $_GET['ticket'] ?? 'N/A';
$tipo = $_GET['tipo'] ?? 'N/A';
$data = date('d/m/Y');
$hora = date('H:i:s');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ticket <?= htmlspecialchars($ticket) ?></title>
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 5mm;
            }

            body {
                font-family: monospace;
                font-size: 13px;
                margin: 0;
                padding: 0;
            }

            .ticket {
                width: 100%;
                text-align: center;
                padding: 0;
                margin: 0;
            }

            .ticket h1 {
                font-size: 24px;
                margin: 0 0 5px 0;
            }

            .ticket p {
                margin: 2px 0;
            }
        }

        body {
            background: #fff;
        }

        .ticket {
            max-width: 300px;
            margin: auto;
            padding: 10px;
            border: 1px dashed #000;
            text-align: center;
        }

        .ticket h1 {
            font-size: 28px;
            margin: 0 0 10px 0;
        }

        .ticket p {
            margin: 5px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <h1><?= htmlspecialchars($ticket) ?></h1>
        <p><strong>Tipo:</strong> <?= htmlspecialchars(ucfirst($tipo)) ?></p>
        <p><strong>Data:</strong> <?= $data ?></p>
        <p><strong>Hora:</strong> <?= $hora ?></p>
        <p>Obrigado pela visita!</p>
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
                window.onafterprint = () => {
                    window.location.href = '../../index.php?page=ticket';
                };
            }, 500);
        };
        
    </script>
</body>
</html>
