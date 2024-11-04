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
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
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
                        <td><?= $item['amount'] ?></td>
                        <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
    </div>
<?php
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = './index.php?page=login'; }, 3000);";
    echo "</script>";
}
?>