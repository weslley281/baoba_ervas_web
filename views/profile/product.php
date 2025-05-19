<!-- Lista de Produtos -->
<?php
if (isset($_SESSION["user_id"]) && $_SESSION['user_type'] == "admin") {
    $get_categories = $category->getAll();
    if (isset($_GET["product"])) {
        $get_product = $product->getBySlogan($_GET["product"]);

?>
        <!-- Formulário de Edição de Produto -->
        <div class="container mt-5">
            <h2 class="text-center mb-4">Editar Produto</h2>
            <form action="./controllers/ProductController.php?action=update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $get_product['product_id']; ?>">
                <input type="hidden" name="old_path_image" value="<?php echo $get_product['path_image']; ?>">

                <div class="form-group mb-3">
                    <label for="name">Nome do Produto</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $get_product['name']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="slogan">Slogan</label>
                    <input type="text" class="form-control" id="slogan" name="slogan" value="<?php echo $get_product['slogan']; ?>" readonly required>
                </div>
                <div class="form-group mb-3">
                    <label for="description">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="6"><?php echo $get_product['description']; ?></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="price">Preço</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $get_product['price']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="discount">Desconto (%)</label>
                    <input type="number" class="form-control" id="discount" name="discount" step="0.01" value="<?php echo $get_product['discount']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="stock_quantity">Quantidade em Estoque</label>
                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?php echo $get_product['stock_quantity']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="reference">Referência</label>
                    <input type="text" class="form-control" id="reference" name="reference" value="<?php echo $get_product['reference']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="image">Imagem</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Área de Visualização da Imagem Selecionada -->
                <div class="container form-group text-center">
                    <img class="img-fluid  text-center" id="imagePreview" src="<?php echo htmlspecialchars($get_product['path_image'], ENT_QUOTES, 'UTF-8'); ?>"
                        style="max-width: 200px; max-height: 200px; display: block;">
                </div>

                <div class="form-group">
                    <label for="category_id">Categoria:</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <?php foreach ($get_categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>"
                                <?php echo ($cat['category_id'] == $get_product['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="active">Ativo?</label>
                    <select class="form-control" name="active" id="active" required>
                        <?php if ($get_product["active"]) : ?>
                            <option value="1" selected>Ativo</option>
                            <option value="0">Inativo</option>
                        <?php else : ?>
                            <option value="0" selected>Inativo</option>
                            <option value="1">Ativo</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-6"><button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Salvar Alterações</button></div>
                    <div class="col-6" style="text-align: right;"><a class="btn btn-secondary" href="index.php?page=profile&action=products"><i class="fa-solid fa-xmark"></i> Cancelar</a></div>
                </div>

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
        <table id="myTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>ref</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Desconto</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $get_products = $product->getAllWithouPagnation();

                foreach ($get_products as $item) :
                    $category_name = $category->getNameById($item['category_id']);
                    $array_path_image = explode("/", $item['path_image']);

                    $path_image = "";

                    foreach ($array_path_image as $key => $value) {
                        if ($key != 0) {
                            $path_image = $path_image . "/" . $value;
                        }
                    }
                ?>
                    <tr>
                        <td><?php echo $item['product_id']; ?></td>
                        <td><?php echo $item['reference']; ?></td>
                        <td><img class="img-fluid rounded border-5" src="<?= '.' . $path_image; ?>" width="50" height="50" alt="Imagem"></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $category_name; ?></td>
                        <td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                        <td><?php echo $item['discount']; ?>%</td>
                        <td><?php echo $item['stock_quantity']; ?></td>
                        <td>
                            <a href="index.php?page=profile&action=products&product=<?php echo $item['slogan']; ?>" class="btn btn-warning btn-sm my-1 mx-1" target="_blank"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="index.php?page=profile&action=products&action2=delete&id=<?php echo $item['product_id']; ?>" class="btn btn-danger btn-sm my-1 mx-1"><i class="fa-solid fa-trash-can"></i></a>
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
                    <form action="./controllers/ProductController.php?action=create" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="active" value="1">
                        <div class="form-group mb-3">
                            <label for="name">Nome do Produto</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price">Preço</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="discount">Desconto (%)</label>
                            <input type="number" class="form-control" id="discount" name="discount" step="0.01" value="0" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="stock_quantity">Quantidade em Estoque</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="1" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reference">Referência</label>
                            <input type="text" class="form-control" id="reference" name="reference" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image">Imagem do Produto</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="category_id">Categoria</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="" disabled selected>Selecione uma Categoria</option>
                                <?php
                                foreach ($get_categories as $cat) {
                                    echo '<option value="' . htmlspecialchars($cat['category_id'], ENT_QUOTES, 'UTF-8') . '">'
                                        . htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8')
                                        . '</option>';
                                }
                                ?>
                            </select>
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
<?php
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = 'index.php?page=login'; }, 3000);";
    echo "</script>";
    exit;
}
?>