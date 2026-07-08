<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db_main.php';
require_once __DIR__ . '/../models/LocalTicket.php';

$ticketModel = new LocalTicket();

$error = $_GET['error'] ?? '';
$success_message = '';

// 1. Processamento de Login Direto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn_main->prepare("SELECT * FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['user_type'] === 'admin' || $user['user_type'] === 'attendant') {
                $_SESSION['attendant_logged'] = true;
                $_SESSION['attendant_user_id'] = $user['user_id'];
                $_SESSION['attendant_name'] = $user['name'];
            } else {
                $error = 'Acesso não autorizado: conta não habilitada para atendimento.';
            }
        } else {
            $error = 'E-mail ou senha incorretos.';
        }
    } else {
        $error = 'Erro na conexão com o banco de dados principal.';
    }
}

// 2. Processamento de Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['attendant_logged']);
    unset($_SESSION['attendant_user_id']);
    unset($_SESSION['attendant_name']);
    unset($_SESSION['attendant_guiche']);
    header("Location: index.php");
    exit;
}

// 3. Processamento de Seleção de Guichê
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'select_guiche') {
    $guiche = intval($_POST['guiche'] ?? 0);
    if ($guiche >= 1 && $guiche <= 10) {
        $_SESSION['attendant_guiche'] = $guiche;
    } else {
        $error = 'Selecione um guichê de 1 a 10.';
    }
}

// 4. Chamada de Senha
$called_ticket = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'call_next') {
    if (isset($_SESSION['attendant_logged']) && isset($_SESSION['attendant_guiche'])) {
        $called_ticket = $ticketModel->callNextTicket($_SESSION['attendant_user_id'], $_SESSION['attendant_guiche']);
        if ($called_ticket) {
            $success_message = "Senha " . $called_ticket['ticket'] . " chamada com sucesso!";
        } else {
            $error = 'Nenhuma senha aguardando atendimento na fila.';
        }
    }
}

// Obtém quantidade na fila e últimas senhas chamadas
$counts = $ticketModel->getQueueCounts();
$recent_calls = $ticketModel->getCalledTickets();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Atendente - Várzea Grande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0fdf4; /* Fundo esverdeado adaptativo para filial */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-green {
            background-color: #0b2e1b; /* Tom verde escuro Baobá */
        }
        .card-custom {
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(11, 46, 27, 0.08);
            border: none;
        }
        .btn-call {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: bold;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #198754, #157347);
        }
    </style>
</head>
<body>

    <!-- Caso NÃO esteja logado -->
    <?php if (!isset($_SESSION['attendant_logged'])): ?>
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="card card-custom p-5 w-100 bg-white" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <h2 class="text-success fw-bold"><i class="fa-solid fa-leaf"></i> Baobá Ervas</h2>
                    <h5 class="text-secondary fw-semibold">Painel de Atendimento</h5>
                    <p class="text-muted small">Várzea Grande</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger py-2 small" role="alert"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3">
                        <label for="email" class="form-label text-secondary fw-semibold">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" required style="border-radius: 8px;">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label text-secondary fw-semibold">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" required style="border-radius: 8px;">
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm" style="border-radius: 8px;">Acessar Painel</button>
                    
                    <div class="text-center my-3 text-muted small">ou entre com sua credencial do Google</div>

                    <!-- Div onde o botão do Google será exibido -->
                    <div class="d-flex justify-content-center">
                        <div id="g_id_onload"
                            data-client_id="495407143203-ikc2mrf5bh2nfppqdfpm34q9e7rdebhi.apps.googleusercontent.com"
                            data-login_uri="login_google.php"
                            data-auto_prompt="false">
                        </div>

                        <div class="g_id_signin"
                            data-type="standard"
                            data-shape="rectangular"
                            data-theme="outline"
                            data-text="signin_with"
                            data-size="large"
                            data-logo_alignment="left"
                            style="width: 100%;">
                        </div>
                    </div>

                    <!-- Script oficial da Google Identity Services -->
                    <script src="https://accounts.google.com/gsi/client" async defer></script>
                </form>
            </div>
        </div>

    <!-- Caso LOGADO mas SEM GUICHÊ selecionado -->
    <?php elseif (!isset($_SESSION['attendant_guiche'])): ?>
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="card card-custom p-5 w-100 bg-white" style="max-width: 450px;">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-success"><i class="fa-solid fa-desktop"></i> Escolha seu Guichê</h3>
                    <p class="text-muted">Olá, <strong><?= htmlspecialchars($_SESSION['attendant_name']) ?></strong>! Identifique sua mesa de atendimento.</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger py-2 small" role="alert"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php">
                    <input type="hidden" name="action" value="select_guiche">
                    <div class="mb-4">
                        <label for="guiche" class="form-label text-secondary fw-semibold">Número do Guichê / Caixa</label>
                        <select name="guiche" id="guiche" class="form-select form-control" required style="border-radius: 8px; height: 50px; font-size: 1.1rem;">
                            <option value="" disabled selected>Selecione seu Guichê...</option>
                            <?php for($i=1; $i<=10; $i++): ?>
                                <option value="<?= $i ?>">Guichê <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm" style="border-radius: 8px;">Confirmar Entrada</button>
                    <div class="text-center mt-3">
                        <a href="index.php?action=logout" class="text-decoration-none text-muted"><small>Sair da Conta</small></a>
                    </div>
                </form>
            </div>
        </div>

    <!-- Caso LOGADO e COM GUICHÊ selecionado -->
    <?php else: ?>
        <!-- Navbar -->
        <nav class="navbar navbar-dark navbar-green shadow-sm mb-5">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-leaf"></i> Baobá Ervas - Várzea Grande</a>
                <div class="d-flex align-items-center text-white">
                    <span class="me-3 mr-3"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['attendant_name']) ?></span>
                    <span class="badge bg-light text-success me-3 mr-3 p-2 fw-bold" style="font-size: 0.95rem;">GUICHÊ <?= $_SESSION['attendant_guiche'] ?></span>
                    <a href="index.php?action=logout" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
                </div>
            </div>
        </nav>

        <!-- Container Principal -->
        <div class="container">
            <div class="row">
                <!-- Painel de Chamada (Esquerda) -->
                <div class="col-lg-7 mb-4">
                    <div class="card card-custom p-4 bg-white mb-4">
                        <h4 class="fw-bold mb-4 text-secondary"><i class="fa-solid fa-bullhorn"></i> Controle de Chamadas</h4>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Aviso:</strong> <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($success_message): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success_message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php" class="text-center my-3">
                            <input type="hidden" name="action" value="call_next">
                            <button type="submit" class="btn btn-call btn-success w-100 py-4 text-white shadow-sm">
                                <i class="fa-solid fa-volume-high fa-xl mb-2 d-block"></i>
                                CHAMAR PRÓXIMO CLIENTE
                            </button>
                        </form>
                    </div>

                    <?php if ($called_ticket): ?>
                        <!-- Ticket Chamado Agora -->
                        <div class="card card-custom p-4 text-center bg-success text-white">
                            <h5 class="text-white-50 text-uppercase mb-2">Chamando Agora</h5>
                            <div class="display-3 fw-bold mb-2"><?= htmlspecialchars($called_ticket['ticket']) ?></div>
                            <p class="mb-0 fs-5">Guichê <?= htmlspecialchars($called_ticket['atendente']) ?> | Hora: <?= htmlspecialchars($called_ticket['hora']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Painel de Filas e Histórico (Direita) -->
                <div class="col-lg-5">
                    <!-- Painel de Filas -->
                    <div class="card card-custom p-4 bg-white mb-4">
                        <h4 class="fw-bold mb-4 text-secondary"><i class="fa-solid fa-people-group"></i> Clientes Aguardando</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="card bg-danger text-white text-center p-3 border-0" style="border-radius: 12px;">
                                    <h6 class="mb-1 text-uppercase text-white-50" style="font-size: 0.75rem; font-weight: 700;">Prioritários</h6>
                                    <div class="fs-1 fw-bold"><?= $counts['priority'] ?></div>
                                    <small style="font-size: 0.75rem;">na fila</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-primary text-white text-center p-3 border-0" style="border-radius: 12px;">
                                    <h6 class="mb-1 text-uppercase text-white-50" style="font-size: 0.75rem; font-weight: 700;">Comuns</h6>
                                    <div class="fs-1 fw-bold"><?= $counts['common'] ?></div>
                                    <small style="font-size: 0.75rem;">na fila</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Últimas Chamadas -->
                    <div class="card card-custom p-4 bg-white">
                        <h4 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-clock-rotate-left"></i> Chamados Recentemente</h4>
                        <div class="list-group">
                            <?php if (empty($recent_calls)): ?>
                                <p class="text-muted text-center py-3 mb-0">Nenhuma senha chamada hoje.</p>
                            <?php else: ?>
                                <?php foreach ($recent_calls as $c): ?>
                                    <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded border-start border-success border-4 bg-light">
                                        <div>
                                            <span class="fs-5 fw-bold text-success"><?= htmlspecialchars($c['ticket']) ?></span>
                                            <small class="text-muted ms-2">(<?= htmlspecialchars($c['hora']) ?>)</small>
                                        </div>
                                        <div class="fw-semibold text-secondary">Guichê <?= htmlspecialchars($c['atendente']) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- JS Bootstrap (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
