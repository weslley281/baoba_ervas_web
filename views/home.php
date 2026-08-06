<!-- Section-->
<section class="py-5">
    <h1 class="visually-hidden d-none">Baobá Brasil - Loja de Produtos Naturais, Chás, Temperos e Cereais</h1>
    <!-- Banner antes dos produtos -->
    <div class="text-center mb-4">
        <img id="banner" src="./images/banner.png" alt="Baobá Brasil - Loja de Produtos Naturais, Chás e Temperos" class="img-fluid rounded-3">
    </div>

    <div class="row text-center">
        <?php
        $categories = $category->getAll();
        foreach ($categories as $cat) {
        ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 my-1">
                <a href="index.php?category_slogan=<?= $cat['slogan'] ?>#products" class="btn btn-outline-success btn-lg w-100 h-auto" role="button" aria-pressed="true"><?= $cat["name"] ?></a>
            </div>
        <?php
        }
        ?>
    </div>

    <h2 class="text-center mb-4">Nossos Produtos</h2>
    <div id="products" class="row row-cols-2 row-cols-md-4 g-4"> <!-- Alterado para g-4 para maior espaçamento entre cards -->

        <?php
        // Determina a página atual e o número de itens por página
        $pagination = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
        $perPage = 12; // Número de produtos por página

        // Obtém produtos com base na busca e na paginação
        if (isset($_GET["search"])) {
            $get_products = $product->getByName($_GET["search"], $pagination, $perPage);
            $totalProducts = count($get_products);
        } elseif (isset($_GET["category_slogan"])) {
            $category_id = $category->getIDBySlogan($_GET["category_slogan"]);
            $get_products = $product->getAllByCategorySlogan($category_id, $pagination, $perPage);
            $totalProducts = $product->getTotalProductsByIdCategory($category_id);
        } else {
            $get_products = $product->getAll($pagination, $perPage);
            $totalProducts = $product->getTotalProducts();
        }

        // Loop para gerar os cards
        foreach ($get_products as $pro) {
            $array_path_image = explode("/", $pro['path_image']);
            $path_image = "";

            foreach ($array_path_image as $key => $value) {
                if ($key != 0) {
                    $path_image = $path_image . "/" . $value;
                }
            }
        ?>
            <div class="col">
                <!-- Link envolve o card inteiro -->
                <a href="index.php?page=product&slogan=<?= $pro['slogan'] ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                    <div class="card h-100 p-3 shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                        <img src="<?= '.' . $path_image; ?>" class="img-fluid mx-auto rounded" alt="<?= $pro["name"] ?>" style="width: 100%; height: auto; object-fit: cover;">
                        <div class="card-body text-center">
                            <h6 class="card-title text-dark"><?= $pro["name"] ?></h6>

                            <div class="text-warning mb-2">
                                <?php 
                                    $rating_data = $product->getRatingAverage($pro['product_id']);
                                    $avg_rating = round($rating_data['average']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $avg_rating) {
                                            echo '<i class="fa-solid fa-star"></i>';
                                        } else {
                                            echo '<i class="fa-regular fa-star text-secondary" style="opacity: 0.5;"></i>';
                                        }
                                    }
                                    if ($rating_data['count'] > 0) {
                                        echo ' <span class="text-muted small" style="font-size: 0.75rem;">(' . $rating_data['count'] . ')</span>';
                                    }
                                ?>
                            </div>

                            <?php
                            $weekly_promo_price = $product->getWeeklyPromotionalPrice($pro['product_id']);
                            $weekdays = [
                                1 => 'Segunda-feira',
                                2 => 'Terça-feira',
                                3 => 'Quarta-feira',
                                4 => 'Quinta-feira',
                                5 => 'Sexta-feira',
                                6 => 'Sábado',
                                7 => 'Domingo'
                            ];
                            $today_day_name = $weekdays[date('N')];
                            
                            if ($weekly_promo_price !== null): ?>
                                <div class="mb-1">
                                    <span class="badge bg-danger text-white" style="font-size: 0.7rem; border-radius: 4px;"><i class="fa-solid fa-tag"></i> Promo de <?= $today_day_name ?></span>
                                </div>
                                <p class="card-text mb-0 text-muted small" style="text-decoration: line-through; font-size: 0.8rem;">R$ <?= number_format($pro['price'], 2, ',', '.') ?></p>
                                <p class="card-text text-danger font-weight-bold mb-0"><strong>R$ <?= number_format($weekly_promo_price, 2, ',', '.') ?></strong></p>
                            <?php elseif ($pro['discount'] > 0): ?>
                                <p class="card-text mb-0 text-muted small" style="text-decoration: line-through; font-size: 0.8rem;">R$ <?= number_format($pro['price'], 2, ',', '.') ?></p>
                                <p class="card-text text-success font-weight-bold mb-0"><strong>R$ <?= number_format($pro['price'] * $pro['discount'], 2, ',', '.') ?></strong></p>
                            <?php else: ?>
                                <p class="card-text text-dark font-weight-bold mb-0"><strong>R$ <?= number_format($pro['price'], 2, ',', '.') ?></strong></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php
        }
        ?>

    </div>

    <!-- Controles de paginação -->
    <nav aria-label="Navegação de página" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php
            $totalPages = ceil($totalProducts / $perPage);
            $nextPage = $pagination < $totalPages ? $pagination + 1 : $totalPages;


            /// Link para a página anterior
            $previousPage = $pagination > 1 ? $pagination - 1 : 1;

            if (isset($_GET["search"])) {
                $searchQuery = '&search=' . $_GET['search'];
            } elseif (isset($_GET["category_slogan"])) {
                $searchQuery = '&category_slogan=' . $_GET["category_slogan"];
            } else {
                $searchQuery = '';
            }
            //$searchQuery = isset($_GET['search']) ? '&search=' . $_GET['search'] : '';
            echo '<li class="page-item ' . ($pagination <= 1 ? 'disabled' : '') . '">
            <a class="page-link" href="?pagination=' . $previousPage . $searchQuery . '#products">Anterior</a>
          </li>';

            // Links para cada página
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = $i === $pagination ? 'active' : '';
                echo "<li class='page-item $active'><a class='page-link' href='?pagination=$i$searchQuery#products'>$i</a></li>";
            }

            // Link para a próxima página
            $nextPage = $pagination < $totalPages ? $pagination + 1 : $totalPages;
            echo '<li class="page-item ' . ($pagination >= $totalPages ? 'disabled' : '') . '">
            <a class="page-link" href="?pagination=' . $nextPage . $searchQuery . '#products">Próximo</a>
          </li>';
            ?>
        </ul>
    </nav>

    <div class="text-center mt-5">
        <img src="./images/zap.svg" alt="Banner da loja" class="img-fluid rounded-3" style="width: 800px; height: 400px;">
    </div>
</section>