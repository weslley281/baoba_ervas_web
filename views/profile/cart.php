<?php
if (isset($_SESSION["user_id"])) {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
?>
        <div class="container my-5">
            <h2 class="text-center mb-4">Seu carrinho está vazio</h2>
        </div>
    <?php
        exit;
    }
    $total = 0;
    ?>
    <div class="container my-5">
        <h2 class="text-center mb-4">Carrinho de Compras</h2>
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
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
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
                                <button type="submit" name="action" value="update_<?= $id ?>" class="btn btn-primary btn-sm my-1 mx-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button type="submit" name="action" value="remove_<?= $id ?>" class="btn btn-danger btn-sm my-1 mx-1"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
        </form>

        <div class="text-center mt-4">
            <?php
            $whatsappMessage = "Olá,%20eu%20sou%20{$_SESSION['name']}-{$_SESSION['user_id']}%20gostaria%20de%20finalizar%20meu%20pedido:%0A";
            foreach ($_SESSION['cart'] as $item) {
                $whatsappMessage .= "Produto:%20{$item['name']}%20|%20Quantidade:%20{$item['amount']}%20|%20Preço:%20R$%20" . number_format($item['price'], 2, ',', '.') . "%0A";
            }
            $whatsappMessage .= "Total:%20R$%20" . number_format($total, 2, ',', '.');
            ?>
            <a href="https://wa.me/5565981233996?text=<?= $whatsappMessage ?>" target="_blank" class="btn btn-success btn-lg">
                Enviar Pedido para WhatsApp
            </a>
        </div>
    </div>
<?php
} else {
    echo "<center><strong><h1>Você não tem permissão para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = './index.php?page=login'; }, 3000);";
    echo "</script>";
}
?>