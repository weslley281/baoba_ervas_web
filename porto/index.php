<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal SGA - Filial Porto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .portal-card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(11, 46, 27, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
        }
        .portal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(11, 46, 27, 0.15);
            color: inherit;
        }
        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
    <div class="container py-5" style="max-width: 900px;">
        <div class="text-center mb-5">
            <h1 class="text-success fw-bold display-5 mb-2"><i class="fa-solid fa-leaf"></i> Baobá Ervas</h1>
            <h2 class="text-secondary fw-semibold fs-4">Sistema de Gerenciamento de Filas - Porto</h2>
            <p class="text-muted">Selecione o módulo que deseja acessar nesta filial</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Totem -->
            <div class="col-md-4">
                <a href="auto-service/" class="card portal-card p-4 text-center h-100 bg-white">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-ticket fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Totem</h4>
                    <p class="text-muted small mb-0">Emissão de senhas para clientes na entrada da loja.</p>
                </a>
            </div>

            <!-- Atendente -->
            <div class="col-md-4">
                <a href="attendant/" class="card portal-card p-4 text-center h-100 bg-white">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-desktop fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Atendente</h4>
                    <p class="text-muted small mb-0">Painel operacional para chamar e gerenciar a fila local.</p>
                </a>
            </div>

            <!-- TV Painel -->
            <div class="col-md-4">
                <a href="display/" class="card portal-card p-4 text-center h-100 bg-white">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-tv fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Painel TV</h4>
                    <p class="text-muted small mb-0">Painel público para exibição das chamadas em tela cheia.</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
