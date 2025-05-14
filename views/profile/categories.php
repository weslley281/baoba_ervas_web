<!-- Lista de Categorias -->
<?php
if (isset($_SESSION["user_id"]) && $_SESSION['user_type'] == "admin") {
    if (isset($_GET["category"])) {
        $get_category = $category->getBySlogan($_GET["category"]);
?>
        <!-- Formulário de Edição de Categoria -->
        <div class="container mt-5">
            <h2 class="text-center mb-4">Editar Categoria</h2>
            <form action="./controllers/CategoryController.php?action=update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $get_category['category_id']; ?>">
                <input type="hidden" name="old_path_image" value="<?php echo $get_category['path_image']; ?>">

                <div class="form-group mb-3">
                    <label for="name">Nome do Categoria</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $get_category['name']; ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="slogan">Slogan</label>
                    <input type="text" class="form-control" id="slogan" name="slogan" value="<?php echo $get_category['slogan']; ?>" readonly>
                </div>

                <div class="form-group mb-3">
                    <label for="description">Descrição</label>
                    <textarea class="form-control" id="description" name="description"><?php echo $get_category['description']; ?></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="image">Imagem do Categoria</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>

                <div class="row">
                    <div class="col-6"><button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Salvar Alterações</button></div>
                    <div class="col-6" style="text-align: right;"><a class="btn btn-secondary" href="index.php?page=profile&action=categories"><i class="fa-solid fa-xmark"></i> Cancelar</a></div>
                </div>
            </form>
        </div>

    <?php
    }
    ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Categorias</h2>
        <div class="row my-3">
            <div class="col-10"></div>
            <div class="col-2">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalCadastrarCategoria">
                    Cadastrar Categoria
                </button>
            </div>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
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
                        <td>
                            <a href="index.php?page=profile&action=categories&category=<?php echo $category['slogan']; ?>" class="btn btn-warning btn-sm" target="_blank">Editar</a>
                            <a href="index.php?page=profile&action=categories&action2=delete&id=<?php echo $category['category_id']; ?>" class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ModalCadastrarCategoria" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalLongoExemplo">Cadastrar Categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./controllers/CategoryController.php?action=create" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="name">Nome do Categoria</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image">Imagem do Categoria</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Criar Categoria</button>
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