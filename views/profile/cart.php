<?php
// Se for a tela de sucesso após finalização
if (isset($_GET['action']) && $_GET['action'] === 'success') {
    $ticket = isset($_GET['ticket']) ? $_GET['ticket'] : '';
    $store_key = isset($_GET['store']) ? $_GET['store'] : '';
    $store_name = isset(STORES[$store_key]) ? STORES[$store_key]['name'] : '';
    $wa_url = isset($_SESSION['whatsapp_order_url']) ? $_SESSION['whatsapp_order_url'] : '';
    
    // Limpa a URL da sessão após usar
    unset($_SESSION['whatsapp_order_url']);
?>
    <div class="container my-5 text-center">
        <div class="card shadow-sm border-0 p-5 mx-auto" style="max-width: 600px; border-radius: 16px; background: #fff;">
            <div class="mb-4">
                <i class="fa-solid fa-circle-check fa-5x text-success animate__animated animate__bounceIn"></i>
            </div>
            <h2 class="text-success fw-bold mb-3">Pedido Gerado com Sucesso!</h2>
            <p class="text-muted mb-4">Seu pedido foi registrado no sistema e o WhatsApp da filial escolhida está sendo aberto para conclusão.</p>
            
            <div class="p-3 mb-4 text-start bg-light border" style="border-radius: 12px; font-size: 0.95rem;">
                <div class="mb-2"><strong>🎫 Código do Pedido:</strong> <span class="badge bg-success" style="font-size: 0.95rem; font-family: monospace;"><?= htmlspecialchars($ticket) ?></span></div>
                <div><strong>📍 Filial de Retirada:</strong> <?= htmlspecialchars($store_name) ?></div>
            </div>

            <?php if (!empty($wa_url)): ?>
                <a href="<?= $wa_url ?>" target="_blank" class="btn btn-success btn-lg w-100 py-3 mb-3 shadow" style="border-radius: 8px; font-weight: bold; background: linear-gradient(135deg, #25D366, #128C7E); border: none;">
                    <i class="fa-brands fa-whatsapp fa-lg mr-2"></i> Enviar Pedido no WhatsApp
                </a>
                <p class="text-muted small mb-0"><i class="fa-solid fa-spinner fa-spin"></i> Tentando abrir o WhatsApp automaticamente...</p>
                <script>
                    setTimeout(function() {
                        window.open('<?= addslashes($wa_url) ?>', '_blank');
                    }, 1500);
                </script>
            <?php else: ?>
                <a href="index.php" class="btn btn-secondary btn-lg w-100 py-3" style="border-radius: 8px;">Voltar para a Página Inicial</a>
            <?php endif; ?>
        </div>
    </div>
<?php
} elseif (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
?>
    <div class="container my-5 text-center">
        <i class="fa-solid fa-cart-shopping fa-5x text-muted mb-4"></i>
        <h2 class="mb-4">Seu carrinho está vazio</h2>
        <a href="index.php" class="btn btn-success btn-lg">Continuar Comprando</a>
    </div>
<?php
} else {
    $total = 0;
    
    // Carrega dados padrão do usuário logado se houver
    $default_name = "";
    $default_phone = "";
    if (isset($_SESSION['user_id'])) {
        require_once __DIR__ . '/../../models/User.php';
        $userModel = new User($conn);
        $u = $userModel->getById($_SESSION['user_id']);
        if ($u) {
            $default_name = $u['name'];
            $default_phone = $u['phone'];
        }
    }
?>
    <div class="container my-5">
        <h2 class="text-center mb-4">Carrinho de Compras</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php
                if ($_GET['error'] === 'missing_fields') {
                    echo 'Por favor, preencha todos os campos obrigatórios do formulário de retirada.';
                } elseif ($_GET['error'] === 'invalid_store') {
                    echo 'A filial selecionada é inválida.';
                } else {
                    echo 'Erro ao processar o seu pedido. Tente novamente.';
                }
                ?>
            </div>
        <?php endif; ?>

        <form id="cart-form" method="post" action="update_cart.php">
            <input type="hidden" id="action-input" name="action" value="">
            
            <!-- LAYOUT DESKTOP -->
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

            <!-- LAYOUT MOBILE -->
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

        <!-- Formulário de Finalização com Identificação (Opção B - Gravando Pedido) -->
        <div class="card shadow-sm border-0 p-4 mt-4" style="border-radius: 12px; background: #fff;">
            <h4 class="text-success fw-bold mb-3"><i class="fa-solid fa-clipboard-user"></i> Informações de Retirada</h4>
            <form id="checkout-form" method="POST" action="./controllers/CheckoutController.php">
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="customer_name" class="form-label text-secondary fw-semibold">Seu Nome:</label>
                        <input type="text" class="form-control" name="customer_name" id="customer_name" required value="<?= htmlspecialchars($default_name) ?>" style="border-radius: 8px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="phone" class="form-label text-secondary fw-semibold">WhatsApp para Contato:</label>
                        <input type="text" class="form-control" name="phone" id="phone" required placeholder="Ex: (65) 99999-9999" value="<?= htmlspecialchars($default_phone) ?>" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="preferred_store" class="form-label text-secondary fw-semibold">Escolha a Filial para Retirar:</label>
                        <select class="form-control" name="preferred_store" id="preferred_store" required style="border-radius: 8px;">
                            <option value="">-- Selecione a Loja --</option>
                            <?php foreach (STORES as $key => $store): ?>
                                <option value="<?= $key ?>" <?= (isset($_SESSION['preferred_store']) && $_SESSION['preferred_store'] == $key) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($store['name']) ?> (<?= htmlspecialchars($store['address']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="payment_method" class="form-label text-secondary fw-semibold">Forma de Pagamento Preferida:</label>
                        <select class="form-control" name="payment_method" id="payment_method" required style="border-radius: 8px;">
                            <option value="">-- Selecione o Pagamento --</option>
                            <option value="pix">Pix</option>
                            <option value="cartao">Cartão de Crédito/Débito na Retirada</option>
                            <option value="dinheiro">Dinheiro na Retirada</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-info py-2 px-3 mt-2" style="border-radius: 8px; font-size: 0.85rem;">
                    <i class="fa-solid fa-circle-info"></i> <strong>Atenção:</strong> Por enquanto, atendemos apenas por **Retirada na Loja física**. O pagamento poderá ser feito no ato da retirada ou antecipado via Pix.
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 py-3 mt-3 shadow" style="border-radius: 8px; background: linear-gradient(135deg, #198754, #157347); border: none; font-weight: bold; font-size: 1.1rem;">
                    <i class="fa-brands fa-whatsapp fa-lg mr-2"></i> Finalizar Pedido e Abrir WhatsApp
                </button>
            </form>
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