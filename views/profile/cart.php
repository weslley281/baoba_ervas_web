<?php
if (isset($_SESSION["user_id"])) {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
?>
        <div class="container my-5">
            <h2 class="text-center mb-4">Seu carrinho está vazio</h2>
        </div>
    <?php
    }
    $total = 0;
    ?>
    <div class="container my-5">
        <h2 class="text-center mb-4">Carrinho de Compras</h2>
        <div id="rotate-warning" class="alert alert-warning text-center d-none" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9999;">
            Para melhor visualização, gire seu dispositivo para o modo paisagem.
        </div>
        <form method="post" action="update_cart.php">
            <table class="table table-hover">
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
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $id => $item): ?>
                            <?php
                            $subtotal = $item['price'] * $item['amount'];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>
                                    <input type="number" name="quantities[<?= $id ?>]" value="<?= $item['amount'] ?>" min="1" class="form-control form-control-sm">
                                </td>
                                <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                <td>
                                    <button type="submit" name="action" value="update_<?= $id ?>" class="btn btn-primary btn-sm my-1 mx-1" title="Editar"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button type="submit" name="action" value="remove_<?= $id ?>" class="btn btn-danger btn-sm my-1 mx-1" title="Remover"><i class="fa-solid fa-trash-can"></i></button>
                                </td>
                            </tr>
                    <?php endforeach;
                    } ?>
                </tbody>
            </table>

            <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
        </form>

        <div class="text-center mt-4">
            <?php
            $whatsappMessage = "Olá,%20eu%20sou%20{$_SESSION['name']}-{$_SESSION['user_id']}%20gostaria%20de%20finalizar%20meu%20pedido:%0A";

            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $whatsappMessage .= "Produto:%20{$item['name']}%20|%20Quantidade:%20{$item['amount']}%20|%20Preço:%20R$%20" . number_format($item['price'], 2, ',', '.') . "%0A";
                }
            }

            $whatsappMessage .= "Total:%20R$%20" . number_format($total, 2, ',', '.');

            if (isset($_SESSION['cart'])) {
            ?>
                <a class="text-dark py-2 px-1" href="#" data-toggle="modal" data-target="#ModalPedidoWhatsapp"><i class="fa-brands fa-whatsapp fa-2x mx-1 my-2"></i> Enviar Pedido para WhatsApp</a>
            <?php } else { ?>
                <p><strong>Adcione itens ao carrinho, para poder mandar sua lista de pedidos por whatsapp</strong></p>
            <?php } ?>
        </div>
    </div>

    <div class="modal fade" id="ModalPedidoWhatsapp" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalLongoExemplo">Quer Pedir para Qual Loja</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a id="widthOutDecoration" href="https://wa.me/556533621007?text=<?= $whatsappMessage ?>" target="_blank">
                        <p class="text-success"><strong>Loja do Centro de Várzea Grande</strong></p>
                    </a>
                    <a id="widthOutDecoration" href="https://wa.me/556533621008?text=<?= $whatsappMessage ?>" target="_blank">
                        <p class="text-success"><strong>Loja do Centro de Cuiabá</strong></p>
                    </a>
                    <a id="widthOutDecoration" href="https://wa.me/556530239010?text=<?= $whatsappMessage ?>" target="_blank">
                        <p class="text-success"><strong>Loja do Porto - Cuiabá</strong></p>
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function isMobileDevice() {
            return /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        }

        function checkOrientation() {
            const warning = document.getElementById('rotate-warning');

            if (isMobileDevice() && window.innerHeight > window.innerWidth) {
                warning.classList.remove('d-none'); // Mostrar aviso
            } else {
                warning.classList.add('d-none'); // Esconder aviso
            }
        }

        window.addEventListener("orientationchange", checkOrientation);
        window.addEventListener("resize", checkOrientation);
        window.addEventListener("load", checkOrientation);
    </script>
<?php
} else {
    echo "<center><strong><h1>Você não tem permissão para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = './index.php?page=login'; }, 3000);";
    echo "</script>";
}
?>