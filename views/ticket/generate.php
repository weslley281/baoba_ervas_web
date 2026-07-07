<?php
$is_kiosk = isset($_GET['kiosk']) && $_GET['kiosk'] == 1;
?>
<div class="container d-flex justify-content-center align-items-center" style="min-height: <?= $is_kiosk ? '90vh' : '75vh' ?>;">
    <div class="card shadow-lg p-5 w-100 border-0" style="max-width: 650px; border-radius: 20px; background: #ffffff;">
        
        <?php if ($is_kiosk): ?>
            <!-- Cabeçalho do Totem com Logo e Boas-Vindas -->
            <div class="text-center mb-4">
                <h1 class="text-success fw-bold font-weight-bold" style="font-size: 2.2rem; letter-spacing: -0.5px;">Baobá Ervas</h1>
                <p class="text-muted" style="font-size: 1.1rem;">Seja bem-vindo! Selecione o tipo de atendimento abaixo.</p>
            </div>
        <?php else: ?>
            <h3 class="text-center mb-4 text-success"><i class="fas fa-ticket-alt"></i> Gerar Ticket</h3>
            <p class="text-center mb-4 text-muted">Selecione o tipo de atendimento abaixo para emitir sua senha.</p>
        <?php endif; ?>

        <div class="row g-4 mt-2">
            <!-- Botão de Senha Comum -->
            <div class="col-12 col-md-6 mb-3">
                <form action="./controllers/TicketController.php?action=generate" method="POST">
                    <input type="hidden" name="tipo" value="comum">
                    <button type="submit" class="btn btn-primary btn-block w-100 py-4 px-3 text-start text-white shadow" style="border-radius: 16px; border: none; background: linear-gradient(135deg, #0d6efd, #0b5ed7); transition: transform 0.2s, box-shadow 0.2s;">
                        <div class="d-flex align-items-center">
                            <div class="me-3 mr-3 bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; flex-shrink: 0;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-white text-left" style="font-weight: 700; font-size: 1.25rem;">Comum</h4>
                                <small class="text-white-50 text-left" style="font-size: 0.8rem; display: block; margin-top: 2px;">Compras gerais e dúvidas</small>
                            </div>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Botão de Senha Prioritária -->
            <div class="col-12 col-md-6 mb-3">
                <form action="./controllers/TicketController.php?action=generate" method="POST">
                    <input type="hidden" name="tipo" value="prioritario">
                    <button type="submit" class="btn btn-danger btn-block w-100 py-4 px-3 text-start text-white shadow" style="border-radius: 16px; border: none; background: linear-gradient(135deg, #dc3545, #bb2d3b); transition: transform 0.2s, box-shadow 0.2s;">
                        <div class="d-flex align-items-center">
                            <div class="me-3 mr-3 bg-white text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; flex-shrink: 0;">
                                <i class="fas fa-user-shield fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-white text-left" style="font-weight: 700; font-size: 1.25rem;">Prioritário</h4>
                                <small class="text-white-50 text-left" style="font-size: 0.8rem; display: block; margin-top: 2px;">Idosos, gestantes e PcD</small>
                            </div>
                        </div>
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-5">
            <?php if ($is_kiosk): ?>
                <a href="index.php?page=ticket" class="btn btn-link text-decoration-none text-muted btn-sm" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-right-from-bracket"></i> Sair do Modo Totem
                </a>
            <?php else: ?>
                <a href="index.php?page=ticket&kiosk=1" class="btn btn-outline-success btn-sm px-3" style="border-radius: 8px;">
                    <i class="fa-solid fa-expand"></i> Ativar Modo Totem (Tela Cheia)
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>