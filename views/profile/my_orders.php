<?php
if (!isset($_SESSION["user_id"])) {
    echo "<center><strong><h1>Acesso Negado</h1></strong></center>";
    exit;
}

require_once __DIR__ . '/../../models/Sale.php';
$saleModel = new Sale($conn);
$my_sales = $saleModel->getSalesByCustomerId($_SESSION['user_id']);

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
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success mb-0 fw-bold"><i class="fa-solid fa-cart-flatbed-suitcase"></i> Meus Pedidos</h2>
    </div>

    <div class="card shadow-sm border-0 p-4" style="border-radius: 12px; background: #fff;">
        <h5 class="text-secondary fw-semibold mb-3">Histórico de Compras</h5>

        <?php if (empty($my_sales)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-basket-shopping fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">Você ainda não realizou nenhum pedido em nosso site.</p>
                <a href="index.php" class="btn btn-success mt-3" style="border-radius: 8px;">Ir para a Loja</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Data/Hora</th>
                            <th>Loja de Retirada</th>
                            <th>Forma de Pagamento</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($my_sales as $s): 
                            $badge_class = $status_badges[$s['situation']] ?? 'badge-secondary bg-secondary text-white';
                            $formatted_date = date('d/m/Y H:i', strtotime($s['createDate']));
                            $store = STORES[$s['preferred_store']] ?? null;
                            $store_name = $store['name'] ?? $s['preferred_store'];
                            $payment_name = $payments_display[$s['payment_method']] ?? $s['payment_method'];
                        ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 0.85rem;"><?= htmlspecialchars($s['ticket_code']) ?></span></td>
                                <td><?= $formatted_date ?></td>
                                <td><strong><?= htmlspecialchars($store_name) ?></strong></td>
                                <td><?= htmlspecialchars($payment_name) ?></td>
                                <td class="fw-bold text-dark">R$ <?= number_format($s['total_price'], 2, ',', '.') ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $badge_class ?> p-2" style="border-radius: 6px; font-size: 0.75rem;">
                                        <?= htmlspecialchars($s['situation']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <!-- Botão Ver Itens -->
                                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#ModalItensPedido_<?= $s['sale_id'] ?>" style="border-radius: 6px;">
                                        <i class="fa-solid fa-list-ul"></i> Ver Itens
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODALS FOR EACH ORDER (Rendered outside containers to prevent Chrome focus and rendering issues) -->
<?php if (!empty($my_sales)): ?>
    <?php foreach ($my_sales as $s): 
        $store = STORES[$s['preferred_store']] ?? null;
        $store_name = $store['name'] ?? $s['preferred_store'];
        $store_address = $store['address'] ?? '';
    ?>
        <!-- MODAL ITENS DO PEDIDO -->
        <div class="modal fade" id="ModalItensPedido_<?= $s['sale_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="LabelItens_<?= $s['sale_id'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="border-radius: 12px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-success fw-bold" id="LabelItens_<?= $s['sale_id'] ?>">Detalhes do Pedido <?= htmlspecialchars($s['ticket_code']) ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3 mb-3 bg-light border" style="border-radius: 8px; font-size: 0.85rem;">
                            <div class="mb-1"><strong>📍 Retirar em:</strong> <?= htmlspecialchars($store_name) ?></div>
                            <div class="text-muted"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($store_address) ?></div>
                        </div>

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
    <?php endforeach; ?>
<?php endif; ?>
