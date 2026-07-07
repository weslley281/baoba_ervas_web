<?php
require_once __DIR__ . '/../models/LocalTicket.php';

$ticketModel = new LocalTicket();
$generated_ticket = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $type = $_POST['type']; // 'comum' ou 'prioritario'
    $generated_ticket = $ticketModel->generateTicket($type);
    if ($generated_ticket) {
        header("Location: print.php?number=" . urlencode($generated_ticket));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Totem de Autoatendimento - Porto</title>
    <!-- Bootstrap CSS (CDN para total independência do microserviço) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0fdf4; /* Fundo verde claro Baobá */
            min-height: 100vh;
        }
        .totem-card {
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(11, 46, 27, 0.1);
        }
        .btn-touch {
            border-radius: 20px;
            border: none;
            padding: 2.5rem 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-touch:active {
            transform: scale(0.97);
        }
        .btn-comum {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        }
        .btn-prioritario {
            background: linear-gradient(135deg, #dc3545, #bb2d3b);
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="container py-5" style="max-width: 800px;">
        <div class="card totem-card border-0 bg-white p-5">
            <!-- Cabeçalho de Boas Vindas -->
            <div class="text-center mb-5">
                <h1 class="text-success fw-bold display-4 mb-2"><i class="fa-solid fa-leaf"></i> Baobá Ervas</h1>
                <h2 class="text-secondary fw-semibold mb-3" style="font-size: 1.5rem;">Filial Várzea Grande</h2>
                <p class="text-muted fs-5">Toque no botão abaixo para retirar a sua senha de atendimento</p>
            </div>

            <!-- Botões de Toque Gigantes -->
            <div class="row g-4 justify-content-center">
                <!-- Atendimento Comum -->
                <div class="col-12 col-md-6">
                    <form method="POST" action="index.php">
                        <input type="hidden" name="type" value="comum">
                        <button type="submit" class="btn btn-touch btn-comum text-white w-100 shadow d-flex flex-column align-items-center justify-content-center">
                            <div class="icon-circle">
                                <i class="fa-solid fa-user fa-2x"></i>
                            </div>
                            <span class="fs-3 fw-bold">Comum</span>
                            <span class="fs-6 text-white-50 mt-2">Dúvidas, pagamentos e retiradas</span>
                        </button>
                    </form>
                </div>

                <!-- Atendimento Prioritário -->
                <div class="col-12 col-md-6">
                    <form method="POST" action="index.php">
                        <input type="hidden" name="type" value="prioritario">
                        <button type="submit" class="btn btn-touch btn-prioritario text-white w-100 shadow d-flex flex-column align-items-center justify-content-center">
                            <div class="icon-circle">
                                <i class="fa-solid fa-universal-access fa-2x"></i>
                            </div>
                            <span class="fs-3 fw-bold">Prioritário</span>
                            <span class="fs-6 text-white-50 mt-2">Idosos, gestantes, PcD e autistas</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Rodapé Informativo -->
            <div class="text-center mt-5 text-muted">
                <small><i class="fa-solid fa-circle-info"></i> Retire o cupom impresso e aguarde ser chamado no painel da TV.</small>
            </div>
        </div>
    </div>
</body>
</html>
