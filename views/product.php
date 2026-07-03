<?php
if (isset($_GET["slogan"])) {
    $get_product = $product->getBySlogan($_GET["slogan"]);
} elseif (isset($_GET["product_id"])) {
    $get_product = $product->getById($_GET["product_id"]);
} else {
    echo "<center><strong><h1>Produto não especificado</h1></strong></center>";
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
    <?php
    switch ("$action") {
        case 'add':
            echo renderAlert('success', 'Sucesso!', 'Produto adcionado ao carrinho com sucesso.');
            break;

        case 'invalid':
            echo renderAlert('warning', 'Aviso!', 'Erro ao editar produto - contate o desenvolvedor do software.');
            break;

        case 'fail':
            echo renderAlert('danger', 'Erro!', 'Erro ao editar produto - contate o desenvolvedor do software.');
            break;
    }
    ?>
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

                <form action="./controllers/CartController.php?action=add" method="POST" class="d-flex flex-wrap align-items-center mt-3">
                    <input type="hidden" name="id" value="<?= $get_product['product_id'] ?>">
                    <input type="hidden" name="name" value="<?= $get_product['name'] ?>">
                    <input type="hidden" name="path_image" value="<?= $path_image ?>">
                    <input type="hidden" name="price" value="<?= $get_product['price'] ?>">
                    <input type="hidden" name="slogan" value="<?= $_GET['slogan'] ?>">
                    <input type="hidden" name="amount" value="1">
                    
                    <button type="submit" class="btn btn-outline-success btn-lg my-1 mr-2"><i class="fa-solid fa-cart-plus"></i> Adicionar ao Carrinho</button>

                    <?php
                    $whatsappMessage = urlencode("Olá! Gostaria de pedir o produto:\n- *" . $get_product['name'] . "* (Ref: " . $get_product['reference'] . ")\nPreço: R$ " . number_format($get_product['price'], 2, ',', '.'));
                    
                    if (isset($_SESSION['preferred_store']) && isset(STORES[$_SESSION['preferred_store']])) {
                        $store = STORES[$_SESSION['preferred_store']];
                        $waLink = "https://wa.me/" . $store['phone'] . "?text=" . $whatsappMessage;
                    ?>
                        <a class="btn btn-success btn-lg my-1 mx-2" href="<?= $waLink ?>" target="_blank"><i class="fa-brands fa-whatsapp"></i> Pedir pelo WhatsApp</a>
                    <?php } else { ?>
                        <button type="button" class="btn btn-success btn-lg my-1 mx-2" data-toggle="modal" data-target="#ModalPedidoWhatsappProduto"><i class="fa-brands fa-whatsapp"></i> Pedir pelo WhatsApp</button>
                    <?php } ?>
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

        <!-- Seção de Produtos Relacionados -->
        <?php
        $stmt_rel = $conn->prepare("SELECT product_id, name, price, path_image, slogan, discount FROM products WHERE category_id = ? AND product_id != ? AND active = 1 LIMIT 4");
        if ($stmt_rel) {
            $stmt_rel->bind_param("ii", $get_product['category_id'], $get_product['product_id']);
            $stmt_rel->execute();
            $res_rel = $stmt_rel->get_result();
            if ($res_rel && $res_rel->num_rows > 0) {
        ?>
                <hr class="my-5">
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="mb-4 text-success"><i class="fa-solid fa-layer-group"></i> Produtos Relacionados</h4>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                            <?php while ($pro_rel = $res_rel->fetch_assoc()) { 
                                $array_path_image_rel = explode("/", $pro_rel['path_image']);
                                $path_image_rel = "";
                                foreach ($array_path_image_rel as $k => $v) {
                                    if ($k != 0) {
                                        $path_image_rel = $path_image_rel . "/" . $v;
                                    }
                                }
                                $price_final = $pro_rel['price'];
                                if ($pro_rel['discount'] > 0) {
                                    $price_final = $pro_rel['price'] * $pro_rel['discount'];
                                }
                            ?>
                                <div class="col my-2">
                                    <a href="index.php?page=product&slogan=<?= $pro_rel['slogan'] ?>" class="text-decoration-none">
                                        <div class="card h-100 p-3 shadow-sm border-0 product-card" style="transition: transform 0.2s, box-shadow 0.2s; border-radius: 12px; background: #fff;">
                                            <img src="<?= '.' . htmlspecialchars($path_image_rel); ?>" class="img-fluid mx-auto rounded animate-hover" alt="<?= htmlspecialchars($pro_rel["name"]) ?>" style="width: 100%; height: 180px; object-fit: cover;">
                                            <div class="card-body text-center p-2">
                                                <h6 class="card-title text-dark mt-2 text-truncate" style="font-size: 0.95rem; font-weight: 600;"><?= htmlspecialchars($pro_rel["name"]) ?></h6>
                                                <div class="text-warning mb-2" style="font-size: 0.8rem;">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>
                                                <p class="card-text text-dark mb-0">
                                                    <?php if ($pro_rel['discount'] > 0): ?>
                                                        <span class="text-muted text-decoration-line-through" style="font-size: 0.8rem;"><s>R$ <?= number_format($pro_rel['price'], 2, ',', '.') ?></s></span><br>
                                                        <strong class="text-success">R$ <?= number_format($price_final, 2, ',', '.') ?></strong>
                                                    <?php else: ?>
                                                        <strong>R$ <?= number_format($price_final, 2, ',', '.') ?></strong>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
    
    <?php if (!isset($_SESSION['preferred_store'])) { ?>
        <!-- Modal Escolha de Loja para WhatsApp -->
        <div class="modal fade" id="ModalPedidoWhatsappProduto" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="TituloModalLongoExemplo">Escolha a Loja para seu Pedido</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Para qual filial você gostaria de enviar o pedido de <strong><?= htmlspecialchars($get_product['name']) ?></strong>?</p>
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
    <?php } ?>
</section>