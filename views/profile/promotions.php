<?php
if (!isset($_SESSION["user_id"]) || $_SESSION['user_type'] !== 'admin') {
    echo "<center><strong><h1>Acesso Negado</h1></strong></center>";
    exit;
}

$productModel = new Product($conn);
$all_products = $productModel->getAllWithouPagnation();
$promotions = $productModel->getAllWeeklyPromotions();

$weekdays = [
    1 => 'Segunda-feira',
    2 => 'Terça-feira',
    3 => 'Quarta-feira',
    4 => 'Quinta-feira',
    5 => 'Sexta-feira',
    6 => 'Sábado',
    7 => 'Domingo'
];

$status_msg = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'saved':
            $status_msg = renderAlert('success', 'Sucesso!', 'Promoção salva com sucesso.');
            break;
        case 'deleted':
            $status_msg = renderAlert('success', 'Sucesso!', 'Promoção excluída com sucesso.');
            break;
        case 'toggled':
            $status_msg = renderAlert('success', 'Sucesso!', 'Status da promoção alterado com sucesso.');
            break;
        case 'invalid':
            $status_msg = renderAlert('warning', 'Aviso!', 'Dados inválidos. Verifique se o preço é maior que zero e preencha todos os campos.');
            break;
        case 'fail':
            $status_msg = renderAlert('danger', 'Erro!', 'Ocorreu um erro ao processar a requisição.');
            break;
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success mb-0 fw-bold"><i class="fa-solid fa-tags"></i> Promoções Semanais</h2>
        <button type="button" class="btn btn-success fw-bold shadow-sm" data-toggle="modal" data-target="#ModalCadastrarPromo">
            <i class="fa-solid fa-plus"></i> Agendar Promoção
        </button>
    </div>

    <?= $status_msg ?>

    <div class="card shadow-sm border-0 p-4" style="border-radius: 12px; background: #fff;">
        <h5 class="text-secondary fw-semibold mb-3">Cronograma de Promoções Ativas</h5>

        <?php if (empty($promotions)): ?>
            <p class="text-muted text-center py-5 mb-0">Nenhuma promoção semanal agendada ainda.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Dia da Semana</th>
                            <th>Produto</th>
                            <th>Preço Original</th>
                            <th>Preço Promocional</th>
                            <th>Desconto Real</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promotions as $p): 
                            $discount_percentage = round((1 - ($p['promotional_price'] / $p['original_price'])) * 100);
                            $day_name = $weekdays[$p['weekday']] ?? '';
                        ?>
                            <tr>
                                <td class="fw-bold text-success"><?= htmlspecialchars($day_name) ?></td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($p['product_name']) ?></td>
                                <td class="text-muted text-decoration-line-through">R$ <?= number_format($p['original_price'], 2, ',', '.') ?></td>
                                <td class="fw-bold text-danger">R$ <?= number_format($p['promotional_price'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger p-1" style="background-color: #f8d7da; color: #842029; border-radius: 4px;">
                                        -<?= $discount_percentage ?>%
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($p['active']): ?>
                                        <a href="./controllers/PromotionController.php?action=toggle&product_id=<?= $p['product_id'] ?>&weekday=<?= $p['weekday'] ?>&price=<?= $p['promotional_price'] ?>&active=0" class="badge p-2" style="background-color: #d1e7dd; color: #0f5132; border-radius: 6px; text-decoration: none; font-size: 0.75rem;">
                                            <i class="fa-solid fa-circle-check"></i> Ativo
                                        </a>
                                    <?php else: ?>
                                        <a href="./controllers/PromotionController.php?action=toggle&product_id=<?= $p['product_id'] ?>&weekday=<?= $p['weekday'] ?>&price=<?= $p['promotional_price'] ?>&active=1" class="badge p-2" style="background-color: #f8d7da; color: #842029; border-radius: 6px; text-decoration: none; font-size: 0.75rem;">
                                            <i class="fa-solid fa-circle-xmark"></i> Inativo
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="./controllers/PromotionController.php?action=delete&id=<?= $p['promotion_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja realmente remover esta promoção?');" style="border-radius: 6px;">
                                        <i class="fa-solid fa-trash-can"></i> Remover
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Cadastrar/Editar Promoção -->
<div class="modal fade" id="ModalCadastrarPromo" tabindex="-1" role="dialog" aria-labelledby="TituloModalPromo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-success fw-bold" id="TituloModalPromo">Agendar Promoção Semanal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controllers/PromotionController.php?action=save" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="product_id" class="form-label text-secondary fw-semibold">Selecione o Produto:</label>
                        <select class="form-control" name="product_id" id="product_id" required style="border-radius: 8px;">
                            <option value="">-- Escolha o produto --</option>
                            <?php foreach ($all_products as $prod): ?>
                                <option value="<?= $prod['product_id'] ?>">
                                    <?= htmlspecialchars($prod['name']) ?> (Original: R$ <?= number_format($prod['price'], 2, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="weekday" class="form-label text-secondary fw-semibold">Dia da Semana:</label>
                        <select class="form-control" name="weekday" id="weekday" required style="border-radius: 8px;">
                            <option value="">-- Escolha o dia --</option>
                            <?php foreach ($weekdays as $num => $day): ?>
                                <option value="<?= $num ?>"><?= htmlspecialchars($day) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="promotional_price" class="form-label text-secondary fw-semibold">Preço Promocional (R$):</label>
                        <input type="number" class="form-control" id="promotional_price" name="promotional_price" step="0.01" min="0.01" placeholder="Ex: 8.50" required style="border-radius: 8px;">
                    </div>

                    <div class="form-group mb-3">
                        <label for="active" class="form-label text-secondary fw-semibold">Status:</label>
                        <select class="form-control" name="active" id="active" required style="border-radius: 8px;">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                    <button type="submit" class="btn btn-success" style="border-radius: 8px; background: linear-gradient(135deg, #198754, #157347); border: none;">Salvar Agendamento</button>
                </div>
            </form>
        </div>
    </div>
</div>
