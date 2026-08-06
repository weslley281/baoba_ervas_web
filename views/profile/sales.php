<?php
if (!isset($_SESSION["user_id"]) || $_SESSION['user_type'] !== 'admin') {
    echo "<center><strong><h1>Acesso Negado</h1></strong></center>";
    exit;
}

require_once __DIR__ . '/../../models/Sale.php';
$saleModel = new Sale($conn);
$all_sales = $saleModel->getAllSales();

// Tradução de lojas e pagamentos
$payments_display = [
    'pix' => 'Pix',
    'cartao' => 'Cartão (Retirada)',
    'dinheiro' => 'Dinheiro (Retirada)'
];

$status_badges = [
    'Pendente' => 'badge-warning bg-warning text-dark',
    'Preparando' => 'badge-primary bg-primary text-white',
    'Pronto para Retirada' => 'badge-info bg-info text-dark',
    'Finalizado' => 'badge-success bg-success text-white',
    'Cancelado' => 'badge-danger bg-danger text-white'
];

$status_msg = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'updated') {
        $status_msg = renderAlert('success', 'Sucesso!', 'Status do pedido atualizado com sucesso.');
    } elseif ($_GET['status'] === 'deleted') {
        $status_msg = renderAlert('success', 'Sucesso!', 'Pedido removido do histórico com sucesso.');
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success mb-0 fw-bold"><i class="fa-solid fa-cart-flatbed-suitcase"></i> Gerenciamento de Pedidos</h2>
    </div>

    <?= $status_msg ?>

    <div class="card shadow-sm border-0 p-4" style="border-radius: 12px; background: #fff;">
        <h5 class="text-secondary fw-semibold mb-3">Histórico de Pedidos Recentes</h5>

        <?php if (empty($all_sales)): ?>
            <p class="text-muted text-center py-5 mb-0">Nenhum pedido registrado no sistema ainda.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Data/Hora</th>
                            <th>Cliente</th>
                            <th>WhatsApp</th>
                            <th>Filial</th>
                            <th>Pagamento</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_sales as $s): 
                            $badge_class = $status_badges[$s['situation']] ?? 'badge-secondary bg-secondary text-white';
                            $formatted_date = date('d/m/Y H:i', strtotime($s['createDate']));
                            $store_name = STORES[$s['preferred_store']]['short_name'] ?? $s['preferred_store'];
                            $payment_name = $payments_display[$s['payment_method']] ?? $s['payment_method'];
                            
                            // Limpa telefone para WhatsApp link
                            $clean_phone = preg_replace('/\D/', '', $s['phone']);
                            if (strlen($clean_phone) == 11 && substr($clean_phone, 0, 2) !== '55') {
                                $clean_phone = '55' . $clean_phone;
                            }
                        ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 0.85rem;"><?= htmlspecialchars($s['ticket_code']) ?></span></td>
                                <td><?= $formatted_date ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($s['customer_name']) ?></td>
                                <td>
                                    <a href="https://wa.me/<?= $clean_phone ?>" target="_blank" class="text-success text-decoration-none fw-semibold">
                                        <i class="fa-brands fa-whatsapp"></i> <?= htmlspecialchars($s['phone']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($store_name) ?></td>
                                <td><?= htmlspecialchars($payment_name) ?></td>
                                <td class="fw-bold text-dark">R$ <?= number_format($s['total_price'], 2, ',', '.') ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $badge_class ?> p-2" style="border-radius: 6px; font-size: 0.75rem;">
                                        <?= htmlspecialchars($s['situation']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Botão Ver Itens -->
                                        <button type="button" class="btn btn-sm btn-outline-success mr-1" data-toggle="modal" data-target="#ModalItensPedido_<?= $s['sale_id'] ?>" style="border-radius: 6px;">
                                            <i class="fa-solid fa-list-ul"></i> Itens
                                        </button>
                                        
                                        <!-- Botão Mudar Status -->
                                        <button type="button" class="btn btn-sm btn-outline-primary mr-1" data-toggle="modal" data-target="#ModalStatusPedido_<?= $s['sale_id'] ?>" style="border-radius: 6px;">
                                            <i class="fa-solid fa-rotate"></i> Status
                                        </button>

                                        <!-- Botão Deletar -->
                                        <a href="./controllers/OrderController.php?action=delete&id=<?= $s['sale_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja realmente excluir este pedido do banco de dados?');" style="border-radius: 6px;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL ITENS DO PEDIDO -->
                            <div class="modal fade" id="ModalItensPedido_<?= $s['sale_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" style="border-radius: 12px;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title text-success fw-bold">Produtos do Pedido <?= htmlspecialchars($s['ticket_code']) ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Nome do Produto</th>
                                                            <th style="width: 15%; text-align: center;">Qtd</th>
                                                            <th style="width: 25%; text-align: right;">Preço</th>
                                                            <th style="width: 25%; text-align: right;">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $items = $saleModel->getSaleItems($s['sale_id']);
                                                        foreach ($items as $item): 
                                                            $sub = $item['price'] * $item['quantity'];
                                                        ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($item['name']) ?></td>
                                                                <td class="text-center"><?= intval($item['quantity']) ?></td>
                                                                <td class="text-end">R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                                                                <td class="text-end fw-semibold">R$ <?= number_format($sub, 2, ',', '.') ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr class="table-light">
                                                            <td colspan="3" class="text-end fw-bold">Total do Pedido:</td>
                                                            <td class="text-end fw-bold text-success">R$ <?= number_format($s['total_price'], 2, ',', '.') ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL ALTERAR STATUS -->
                            <div class="modal fade" id="ModalStatusPedido_<?= $s['sale_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content" style="border-radius: 12px;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title text-success fw-bold">Alterar Status</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="./controllers/OrderController.php?action=update_status" method="POST">
                                            <input type="hidden" name="sale_id" value="<?= $s['sale_id'] ?>">
                                            <div class="modal-body">
                                                <div class="form-group mb-0">
                                                    <label for="situation_<?= $s['sale_id'] ?>" class="form-label text-secondary fw-semibold">Situação do Pedido:</label>
                                                    <select class="form-control" name="situation" id="situation_<?= $s['sale_id'] ?>" required style="border-radius: 8px;">
                                                        <option value="Pendente" <?= $s['situation'] === 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                                                        <option value="Preparando" <?= $s['situation'] === 'Preparando' ? 'selected' : '' ?>>Preparando</option>
                                                        <option value="Pronto para Retirada" <?= $s['situation'] === 'Pronto para Retirada' ? 'selected' : '' ?>>Pronto para Retirada</option>
                                                        <option value="Finalizado" <?= $s['situation'] === 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
                                                        <option value="Cancelado" <?= $s['situation'] === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Cancelar</button>
                                                <button type="submit" class="btn btn-success btn-sm" style="border-radius: 6px; background: linear-gradient(135deg, #198754, #157347); border: none;">Salvar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
