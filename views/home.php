<!-- Section-->
<section class="py-5">
    <!-- Banner antes dos produtos -->
    <div class="text-center mb-4">
        <img src="./images/banner.svg" alt="Banner da loja" class="img-fluid rounded" style="width: 1000px; height: 400px;">
    </div>

    <h2 class="text-center mb-4">Nossos Produtos</h2>
    <div class="row row-cols-1 row-cols-md-3 g-3">

        <?php
        // Array de produtos
        $get_products = $product->getAll();

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

    <div class="text-center mt-5 mb-2">
        <img src="./images/zap.svg" alt="Banner da loja" class="img-fluid rounded" style="width: 800px; height: 400px;">
    </div>
</section>