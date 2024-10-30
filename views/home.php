<!-- Section-->
<section class="py-5">
    <h2 class="text-center mb-4">Nossos Produtos</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php
        // Array de produtos
        $produtos = [
            [
                "nome" => "Produto 1",
                "descricao" => "Descrição breve do produto 1.",
                "preco" => "99.90",
                "imagem" => "https://via.placeholder.com/150"
            ],
            [
                "nome" => "Produto 2",
                "descricao" => "Descrição breve do produto 2.",
                "preco" => "129.90",
                "imagem" => "https://via.placeholder.com/150"
            ],
            [
                "nome" => "Produto 3",
                "descricao" => "Descrição breve do produto 3.",
                "preco" => "79.90",
                "imagem" => "https://via.placeholder.com/150"
            ]
        ];

        // Loop para gerar os cards
        foreach ($produtos as $produto) {
        ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?= $produto["imagem"] ?>" class="card-img-top" alt="<?= $produto["nome"] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $produto["nome"] ?></h5>
                        <p class="card-text"><?= $produto["descricao"] ?></p>
                        <p class="card-text"><strong>R$ <?= $produto["preco"] ?></strong></p>
                        <a href="#" class="btn btn-primary">Adicionar ao Carrinho</a>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>

    </div>
</section>