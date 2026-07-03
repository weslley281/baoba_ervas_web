<?php
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
?>
    <div class="container my-5 text-center">
        <i class="fa-solid fa-cart-shopping fa-5x text-muted mb-4"></i>
        <h2 class="mb-4">Seu carrinho está vazio</h2>
        <a href="index.php" class="btn btn-success btn-lg">Continuar Comprando</a>
    </div>
<?php
} else {
    $total = 0;
?>
    <div class="container my-5">
        <h2 class="text-center mb-4">Carrinho de Compras</h2>
        
        <form id="cart-form" method="post" action="update_cart.php">
            <input type="hidden" id="action-input" name="action" value="">
            
            <!-- LAYOUT DESKTOP: Exibido apenas em telas médias e grandes -->
            <table class="table table-hover align-middle d-none d-md-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($_SESSION['cart'] as $id => $item): 
                        $subtotal = $item['price'] * $item['amount'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                            <td>
                                <div class="input-group input-group-sm" style="max-width: 120px;">
                                    <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQuantity('<?= $id ?>', -1)">-</button>
                                    <input type="number" id="qty_<?= $id ?>" name="quantities[<?= $id ?>]" value="<?= $item['amount'] ?>" min="1" class="form-control text-center bg-white" readonly style="width: 50px;">
                                    <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQuantity('<?= $id ?>', 1)">+</button>
                                </div>
                            </td>
                            <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                            <td>
                                <button type="button" onclick="removeItem('<?= $id ?>')" class="btn btn-danger btn-sm" title="Remover"><i class="fa-solid fa-trash-can"></i> Remover</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- LAYOUT MOBILE: Exibido apenas em telas pequenas (Celulares) -->
            <div class="d-block d-md-none">
                <?php foreach ($_SESSION['cart'] as $id => $item): 
                    $subtotal = $item['price'] * $item['amount'];
                ?>
                    <div class="card p-3 mb-3 border shadow-sm" style="border-radius: 12px; background: #fff;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 1rem;"><?= htmlspecialchars($item['name']) ?></h6>
                            <button type="button" onclick="removeItem('<?= $id ?>')" class="btn btn-outline-danger btn-sm border-0" title="Remover"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                        <hr class="my-2 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="input-group input-group-sm" style="max-width: 110px;">
                                <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQuantity('<?= $id ?>', -1)">-</button>
                                <input type="number" id="qty_mobile_<?= $id ?>" value="<?= $item['amount'] ?>" min="1" class="form-control text-center bg-white font-monospace" readonly>
                                <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQuantity('<?= $id ?>', 1)">+</button>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.8rem;">Preço: R$ <?= number_format($item['price'], 2, ',', '.') ?></small>
                                <strong class="text-success" style="font-size: 0.95rem;">Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Box do Total Geral -->
            <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-white border rounded-3 shadow-sm">
                <span class="fs-5 fw-bold text-secondary">Valor Total:</span>
                <span class="fs-4 fw-bold text-success">R$ <?= number_format($total, 2, ',', '.') ?></span>
            </div>
        </form>

        <div class="text-center mt-4">
            <?php
            $whatsappText = "Olá! Gostaria de fazer o seguinte pedido de produtos:\n\n";
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $whatsappText .= "• *" . $item['name'] . "* | Qtd: " . $item['amount'] . " | Preço: R$ " . number_format($item['price'], 2, ',', '.') . "\n";
                }
            }
            $whatsappText .= "\n*Total:* R$ " . number_format($total, 2, ',', '.');
            $whatsappMessage = urlencode($whatsappText);
            ?>

            <div class="text-center mt-4">
                <?php
                if (isset($_SESSION['preferred_store']) && isset(STORES[$_SESSION['preferred_store']])) {
                    $store = STORES[$_SESSION['preferred_store']];
                    $waLink = "https://wa.me/" . $store['phone'] . "?text=" . $whatsappMessage;
                ?>
                    <a class="btn btn-success btn-lg py-3 px-4 my-2 text-white w-100" href="<?= $waLink ?>" target="_blank"><i class="fa-brands fa-whatsapp fa-xl mr-2"></i> Enviar Pedido para WhatsApp (<?= htmlspecialchars($store['short_name']) ?>)</a>
                <?php } else { ?>
                    <a class="btn btn-success btn-lg py-3 px-4 my-2 text-white w-100" href="#" data-toggle="modal" data-target="#ModalPedidoWhatsapp"><i class="fa-brands fa-whatsapp fa-xl mr-2"></i> Enviar Pedido para WhatsApp</a>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Modal Escolha de Loja -->
    <div class="modal fade" id="ModalPedidoWhatsapp" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalLongoExemplo">Escolha a Filial para seu Pedido</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Selecione uma filial para enviar a lista de compras:</p>
                    <div class="list-group mt-3">
                        <?php foreach (STORES as $key => $store) { 
                            $waLink = "https://wa.me/" . $store['phone'] . "?text=" . $whatsappMessage;
                        ?>
                            <a href="<?= $waLink ?>" target="_blank" class="list-group-item list-group-item-action text-success py-3">
                                <i class="fa-brands fa-whatsapp fa-lg"></i> <strong><?= htmlspecialchars($store['name']) ?></strong>
                                <br><small class="text-muted"><?= htmlspecialchars($store['address']) ?></small>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<script>
    function changeQuantity(id, delta) {
        const desktopInput = document.getElementById('qty_' + id);
        const mobileInput = document.getElementById('qty_mobile_' + id);
        const input = desktopInput || mobileInput;
        const actionInput = document.getElementById('action-input');
        const form = document.getElementById('cart-form');
        
        if (input && actionInput && form) {
            let val = parseInt(input.value) || 1;
            val += delta;
            if (val < 1) val = 1;
            input.value = val;
            
            // Sincroniza ambos os campos caso existam no DOM
            if (desktopInput) desktopInput.value = val;
            if (mobileInput) mobileInput.value = val;
            
            actionInput.value = 'update_' + id;
            form.submit();
        }
    }

    function removeItem(id) {
        const actionInput = document.getElementById('action-input');
        const form = document.getElementById('cart-form');
        
        if (actionInput && form) {
            actionInput.value = 'remove_' + id;
            form.submit();
        }
    }
</script>