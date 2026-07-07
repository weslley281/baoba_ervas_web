<?php
require_once __DIR__ . '/../../models/Ticket.php';
$counts = Ticket::getQueueCounts();
$lastAtendente = $_SESSION['last_called_atendente'] ?? '';
?>
<div class="container mt-5" style="max-width: 600px;">
    <!-- Painel informativo de filas para o atendente -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="card bg-danger text-white text-center p-3 shadow-sm border-0" style="border-radius: 12px;">
                <h6 class="mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 700;">Prioritários</h6>
                <div class="fs-2 fw-bold" style="font-size: 2rem;"><?= $counts['priority'] ?></div>
                <small style="font-size: 0.75rem; opacity: 0.9;">na fila</small>
            </div>
        </div>
        <div class="col-6">
            <div class="card bg-primary text-white text-center p-3 shadow-sm border-0" style="border-radius: 12px;">
                <h6 class="mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 700;">Comuns</h6>
                <div class="fs-2 fw-bold" style="font-size: 2rem;"><?= $counts['common'] ?></div>
                <small style="font-size: 0.75rem; opacity: 0.9;">na fila</small>
            </div>
        </div>
    </div>

    <!-- Card de chamada -->
    <div class="card p-4 shadow border-0" style="border-radius: 16px; background: #fff;">
        <h3 class="text-center mb-4 text-success"><i class="fas fa-bullhorn"></i> Chamar Próximo</h3>
        
        <form action="./controllers/TicketController.php?action=call" method="POST">
            <p class="text-center text-muted mb-4">Selecione seu número de identificação e clique para chamar.</p>

            <div class="form-group mb-4">
                <label for="atendente" class="form-label fw-semibold text-secondary">Seu Guichê / Mesa</label>
                <select name="atendente" id="atendente" class="form-select form-control" required style="border-radius: 8px;">
                    <option value="" disabled <?= empty($lastAtendente) ? 'selected' : '' ?>>Selecione o Guichê...</option>
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>" <?= $lastAtendente == $i ? 'selected' : '' ?>>Guichê <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success btn-lg w-100 py-3 text-white fw-bold shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-volume-up"></i> Chamar Próximo Cliente
            </button>
        </form>
    </div>
</div>
