<!-- Section-->
<section class="py-5">
    <h2 class="text-center mb-4">Nossos Produtos</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">

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
                <div class="card h-100">
                    <img src="<?= '.' . $path_image; ?>" class="card-img-top" alt="<?= $pro["name"] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $pro["name"] ?></h5>
                        <p class="card-text"><?= htmlspecialchars_decode($pro["description"], ENT_QUOTES) ?></p>
                        <p class="card-text"><strong>R$ <?= $pro["price"] ?></strong></p>
                        <?php if (isset($_SESSION["user_id"])) { ?>
                        <a href="#" class="btn btn-primary">Adicionar ao Carrinho</a>
                        <?php } ?>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>

    </div>
</section>