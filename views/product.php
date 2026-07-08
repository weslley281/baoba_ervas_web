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
                
                <div class="text-warning mb-3">
                    <?php 
                        $rating_data = $product->getRatingAverage($get_product['product_id']);
                        $avg_rating = round($rating_data['average']);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $avg_rating) {
                                echo '<i class="fa-solid fa-star"></i>';
                            } else {
                                echo '<i class="fa-regular fa-star text-secondary" style="opacity: 0.5;"></i>';
                            }
                        }
                        if ($rating_data['count'] > 0) {
                            echo ' <span class="text-muted small" style="font-size: 0.9rem; font-weight: 500;">' . number_format($rating_data['average'], 1) . ' (' . $rating_data['count'] . ' avaliações)</span>';
                        } else {
                            echo ' <span class="text-muted small" style="font-size: 0.9rem;">(Sem avaliações ainda)</span>';
                        }
                    ?>
                </div>

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

        <!-- Seção de Avaliações -->
        <hr class="my-5">
        <div class="row">
            <div class="col-md-7 mb-4">
                <h4 class="text-success mb-4"><i class="fa-solid fa-comments"></i> Opinião dos Clientes</h4>
                <?php
                $ratings = $product->getRatings($get_product['product_id']);
                if (empty($ratings)):
                ?>
                    <p class="text-muted">Nenhum cliente avaliou este produto ainda. Seja o primeiro!</p>
                <?php else: ?>
                    <div style="max-height: 450px; overflow-y: auto; padding-right: 5px;">
                        <?php foreach ($ratings as $r): ?>
                            <div class="border-0 bg-light rounded p-3 mb-3 shadow-sm" style="border-radius: 12px !important;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-dark"><i class="fa-solid fa-user-circle text-success me-1"></i> <?= htmlspecialchars($r['user_name']) ?></strong>
                                    <span class="text-muted small" style="font-size: 0.8rem;"><?= date('d/m/Y', strtotime($r['createDate'])) ?></span>
                                </div>
                                <div class="text-warning mb-2" style="font-size: 0.85rem;">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $r['rating']) {
                                            echo '<i class="fa-solid fa-star"></i>';
                                        } else {
                                            echo '<i class="fa-regular fa-star text-secondary" style="opacity: 0.5;"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <?php if (!empty($r['comment'])): ?>
                                    <p class="mb-0 text-secondary" style="font-size: 0.95rem; line-height: 1.5;"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($r['admin_reply'])): ?>
                                    <div class="mt-3 p-3 bg-white border-start border-success border-3 rounded shadow-sm" style="border-left: 4px solid #198754 !important; border-radius: 8px !important;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-success" style="font-size: 0.9rem;"><i class="fa-solid fa-shield-halved"></i> Resposta da Baobá Ervas</strong>
                                            <span class="text-muted small" style="font-size: 0.75rem;"><?= date('d/m/Y', strtotime($r['replyDate'])) ?></span>
                                        </div>
                                        <p class="mb-0 text-dark" style="font-size: 0.9rem; line-height: 1.4;"><?= nl2br(htmlspecialchars($r['admin_reply'])) ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-success" type="button" data-toggle="collapse" data-target="#reply-form-<?= $r['rating_id'] ?>" aria-expanded="false" aria-controls="reply-form-<?= $r['rating_id'] ?>">
                                            <i class="fa-solid fa-reply"></i> <?= empty($r['admin_reply']) ? 'Responder' : 'Editar Resposta' ?>
                                        </button>
                                        <div class="collapse mt-2" id="reply-form-<?= $r['rating_id'] ?>">
                                            <form action="./controllers/RatingController.php" method="POST" class="p-3 bg-white border rounded shadow-sm" style="border-radius: 8px !important;">
                                                <input type="hidden" name="action" value="reply">
                                                <input type="hidden" name="rating_id" value="<?= $r['rating_id'] ?>">
                                                <input type="hidden" name="slogan" value="<?= htmlspecialchars($_GET['slogan'] ?? '') ?>">
                                                <div class="mb-2">
                                                    <label for="admin_reply_<?= $r['rating_id'] ?>" class="form-label text-secondary small fw-semibold">Resposta do Admin:</label>
                                                    <textarea name="admin_reply" id="admin_reply_<?= $r['rating_id'] ?>" class="form-control form-control-sm" rows="2" style="resize: none;" required><?= htmlspecialchars($r['admin_reply'] ?? '') ?></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-success">Salvar Resposta</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-5">
                <div class="card card-body shadow-sm border-0 p-4 mb-4" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;">
                    <h4 class="text-success mb-3"><i class="fa-solid fa-pen-to-square"></i> Avaliar Produto</h4>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] === 'rating_saved'): ?>
                        <div class="alert alert-success py-2 small" role="alert">
                            Avaliação salva com sucesso! Obrigado pela sua opinião.
                        </div>
                    <?php elseif (isset($_GET['success']) && $_GET['success'] === 'reply_saved'): ?>
                        <div class="alert alert-success py-2 small" role="alert">
                            Resposta do administrador salva com sucesso!
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php 
                        $user_rating = $product->getUserRating($get_product['product_id'], $_SESSION['user_id']);
                        ?>
                        <form action="./controllers/RatingController.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $get_product['product_id'] ?>">
                            <input type="hidden" name="slogan" value="<?= htmlspecialchars($_GET['slogan'] ?? '') ?>">
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold mb-1">Sua nota:</label>
                                <div class="d-flex flex-row-reverse justify-content-end gap-2 star-rating-form">
                                    <input type="radio" id="form-star5" name="rating" value="5" <?= (isset($user_rating['rating']) && $user_rating['rating'] == 5) ? 'checked' : '' ?> required>
                                    <label for="form-star5" title="5 estrelas"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="form-star4" name="rating" value="4" <?= (isset($user_rating['rating']) && $user_rating['rating'] == 4) ? 'checked' : '' ?>>
                                    <label for="form-star4" title="4 estrelas"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="form-star3" name="rating" value="3" <?= (isset($user_rating['rating']) && $user_rating['rating'] == 3) ? 'checked' : '' ?>>
                                    <label for="form-star3" title="3 estrelas"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="form-star2" name="rating" value="2" <?= (isset($user_rating['rating']) && $user_rating['rating'] == 2) ? 'checked' : '' ?>>
                                    <label for="form-star2" title="2 estrelas"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="form-star1" name="rating" value="1" <?= (isset($user_rating['rating']) && $user_rating['rating'] == 1) ? 'checked' : '' ?>>
                                    <label for="form-star1" title="1 estrela"><i class="fa-solid fa-star"></i></label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comment" class="form-label text-secondary fw-semibold mb-1">Seu comentário (opcional):</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3" style="border-radius: 8px; resize: none;" placeholder="Escreva o que achou do produto..."><?= htmlspecialchars($user_rating['comment'] ?? '') ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #198754, #157347); border: none;">Enviar Avaliação</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <p class="text-muted small mb-3">Você precisa estar logado para avaliar este produto.</p>
                            <a href="index.php?page=login" class="btn btn-outline-success fw-bold" style="border-radius: 8px;">Fazer Login</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <style>
            .star-rating-form {
                direction: rtl;
                display: flex;
            }
            .star-rating-form input[type="radio"] {
                display: none;
            }
            .star-rating-form label {
                font-size: 1.8rem;
                color: #ccc;
                cursor: pointer;
                transition: color 0.2s, transform 0.1s;
                margin-right: 5px;
            }
            .star-rating-form label:hover {
                transform: scale(1.15);
            }
            .star-rating-form input[type="radio"]:checked ~ label {
                color: #FFD700;
            }
            .star-rating-form label:hover,
            .star-rating-form label:hover ~ label {
                color: #FFD700;
            }
        </style>

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
                                                    <?php 
                                                        $rating_rel = $product->getRatingAverage($pro_rel['product_id']);
                                                        $avg_rating_rel = round($rating_rel['average']);
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            if ($i <= $avg_rating_rel) {
                                                                echo '<i class="fa-solid fa-star"></i>';
                                                            } else {
                                                                echo '<i class="fa-regular fa-star text-secondary" style="opacity: 0.5;"></i>';
                                                            }
                                                        }
                                                    ?>
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