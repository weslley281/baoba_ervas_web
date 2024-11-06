<?php
if (isset($_GET["slogan"])) {
    $get_product = $product->getBySlogan($_GET["slogan"]);
} elseif (isset($_GET["product_id"])) {
    $get_product = $product->getById($_GET["product_id"]);
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = 'index.php'; }, 3000);";
    echo "</script>";
    exit;
}

$array_path_image = explode("/", $get_product['path_image']);

$path_image = "";

foreach ($array_path_image as $key => $value) {
    if ($key != 0) {
        $path_image = $path_image . "/" . $value;
    }
}
?>
<!-- Section-->
<section class="py-5">
    <h2 class="text-center mb-4">Nossos Produtos</h2>
    <!-- Container principal -->
    <div class="container my-5">
        <!-- Imagem do Produto -->
        <div class="row">
            <div class="col-md-6 my-2">
                ​<picture>
                    <img src="<?= '.' . htmlspecialchars($path_image); ?>" class="img-fluid img-thumbnail rounded mx-auto d-block" alt="<?= htmlspecialchars($get_product['name']) ?>">
                </picture>
            </div>

            <!-- Detalhes do Produto -->
            <div class="col-md-6 my-2">
                <h1><?= htmlspecialchars($get_product['name']) ?></h1>

                <?php if ($get_product['discount'] > 0): ?>
                    <p class="text-danger">De: <s>R$ <?= number_format($get_product['price'], 2, ',', '.') ?></s></p>
                    <h3 class="text-success">Por: R$ <?= number_format($get_product['price'] * $get_product['discount'], 2, ',', '.') ?></h3>
                <?php else: ?>
                    <h3>R$ <?= number_format($get_product['price'], 2, ',', '.') ?></h3>
                <?php endif; ?>

                <p><strong>Estoque:</strong> <?= intval($get_product['stock_quantity']) ?> unidades</p>
                <p><strong>Referência:</strong> <?= htmlspecialchars($get_product['reference']) ?></p>

                <form action="./controllers/CartController.php?action=add" method="POST">
                    <input type="hidden" name="id" value="<?= $get_product['product_id'] ?>">
                    <input type="hidden" name="name" value="<?= $get_product['name'] ?>">
                    <input type="hidden" name="path_image" value="<?= $path_image ?>">
                    <input type="hidden" name="price" value="<?= $get_product['price'] ?>">
                    <input type="hidden" name="slogan" value="<?= $_GET['slogan'] ?>">
                    <input type="hidden" name="amount" value="1">
                    <button type="submit" class="btn btn-info btn-lg"><i class="fa-solid fa-cart-shopping"></i> Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>

        <!-- Descrição do Produto -->
        <div class="row mt-5">
            <div class="col-12">
                <h4>Descrição</h4>
                <p><?= htmlspecialchars_decode($get_product["description"], ENT_QUOTES) ?></p>
            </div>
        </div>

        <!-- Data de criação e edição -->
        <div class="row mt-3">
            <div class="col-12">
                <p class="text-muted"><small>Criado em: <?= date('d/m/Y', strtotime($get_product['createDate'])) ?></small></p>
                <p class="text-muted"><small>Última atualização: <?= date('d/m/Y', strtotime($get_product['editDate'])) ?></small></p>
            </div>
        </div>
    </div>
</section>