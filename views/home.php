<!-- Section-->
<section class="py-5">
    <!-- Banner antes dos produtos -->
    <div class="text-center mb-4">
        <img id="banner" src="./images/banner.png" alt="Banner da loja" class="img-fluid rounded-3">
    </div>

    <h2 class="text-center mb-4">Nossos Produtos</h2>
    <div id="products" class="row row-cols-1 row-cols-md-3 g-3">

        <?php
        // Determina a página atual e o número de itens por página
        $pagination = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
        $perPage = 9; // Número de produtos por página

        // Obtém produtos com base na busca e na paginação
        if (isset($_GET["search"])) {
            $get_products = $product->getByName($_GET["search"], $pagination, $perPage);
        } else {
            $get_products = $product->getAll($pagination, $perPage);
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
                    <div class="card h-100" style="min-width: 220px; padding: 10px; transition: transform 0.2s, box-shadow 0.2s;">
                        <img src="<?= '.' . $path_image; ?>" class="card-img-top" alt="<?= $pro["name"] ?>" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title text-dark"><?= $pro["name"] ?></h6>
                            <p class="card-text text-dark"><strong>R$ <?= $pro["price"] ?></strong></p>
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
            // Determina o número total de produtos para calcular o total de páginas
            $totalProducts = count($product->getAll()); // Pode-se criar um método específico para contar todos os produtos
            $totalPages = ceil($totalProducts / $perPage);

            // Link para a página anterior
            $previousPage = $pagination > 1 ? $pagination - 1 : 1;
            $searchQuery = isset($_GET['search']) ? '&search=' . $_GET['search'] : '';
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

    <div class="text-center mt-5 mb-2">
        <img src="./images/zap.svg" alt="Banner da loja" class="img-fluid rounded-3" style="width: 800px; height: 400px;">
    </div>
</section>