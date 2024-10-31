<!-- Lista de Produtos -->
<?php
if (isset($_GET["category"])) {
    $get_category = $category->getBySlogan($_GET["category"]);
?>
    <!-- Formulário de Edição de Produto -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Produto</h2>
        <form action="./controllers/categoryController.php?action=update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $get_category['category_id']; ?>">
            <input type="hidden" name="old_path_image" value="<?php echo $get_category['path_image']; ?>">

            <div class="form-group mb-3">
                <label for="name">Nome do Produto</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $get_category['name']; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="slogan">Slogan</label>
                <input type="text" class="form-control" id="slogan" name="slogan" value="<?php echo $get_category['slogan']; ?>">
            </div>

            <div class="form-group mb-3">
                <label for="description">Descrição</label>
                <textarea class="form-control" id="description" name="description"><?php echo $get_category['description']; ?></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="image">Imagem do Produto</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>

<?php
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Lista de Produtos</h2>
    <div class="row my-3">
        <div class="col-10"></div>
        <div class="col-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalCadastrarProduto">
                Cadastrar Produto
            </button>
        </div>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Desconto</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $get_categories = $category->getAll();

            foreach ($get_categories as $category) :
                $array_path_image = explode("/", $category['path_image']);

                $path_image = "";

                foreach ($array_path_image as $key => $value) {
                    if ($key != 0) {
                        $path_image = $path_image . "/" . $value;
                    }
                }
            ?>
                <tr>
                    <td><img src="<?= "." . $path_image; ?>" width="50" height="50" alt="Imagem"></td>
                    <td><?php echo $category['name']; ?></td>
                    <td>R$ <?php echo number_format($category['price'], 2, ',', '.'); ?></td>
                    <td><?php echo $category['discount']; ?>%</td>
                    <td><?php echo $category['stock_quantity']; ?></td>
                    <td>
                        <a href="index.php?page=profile&action=categories&category=<?php echo $category['slogan']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="index.php?page=profile&action=categories&action2=delete&id=<?php echo $category['category_id']; ?>" class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalCadastrarProduto" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalLongoExemplo">Cadastrar Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./controllers/categoryController.php?action=create" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="name">Nome do Produto</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Descrição</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Preço</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="discount">Desconto (%)</label>
                        <input type="number" class="form-control" id="discount" name="discount" step="0.01">
                    </div>

                    <div class="form-group mb-3">
                        <label for="stock_quantity">Quantidade em Estoque</label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="reference">Referência</label>
                        <input type="text" class="form-control" id="reference" name="reference">
                    </div>

                    <div class="form-group mb-3">
                        <label for="image">Imagem do Produto</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>

                    <button type="submit" class="btn btn-primary">Criar Produto</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>