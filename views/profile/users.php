<!-- Lista de Usuarios -->
<?php
if (isset($_SESSION["user_id"]) && $_SESSION['user_type'] == "admin") {
    $get_categories = $category->getAll();
    if (isset($_GET["user"])) {
        $get_user = $user->getById($_GET["user"]);

?>
        <!-- Formulário de Edição de Usuario -->
        <div class="container mt-5">
            <h2 class="text-center mb-4">Editar Usuario</h2>
            <form action="./controllers/UserController.php?action=update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $get_user['user_id']; ?>">

                <div class="form-group mb-3">
                    <label for="name">Nome do Usuario</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $get_user['name']; ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $get_user['email']; ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="email">Endereço de email</label>
                    <input type="email" class="form-control" id="email" value="<?= $get_user["email"] ?>" name="email">
                </div>

                <div class="form-group">
                    <label for="postal_code">Código Postal</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="postal_code" maxlength="9"
                            value="<?= $get_user["postal_code"] ?>" name="postal_code"
                            oninput="mascaraCep(this)" placeholder="00000-000">
                        <button type="button" class="btn btn-secondary" onclick="buscarCep()">Buscar</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Endereço</label>
                    <input type="text" class="form-control" id="address" value="<?= $get_user["address"] ?>" name="address">
                </div>

                <div class="form-group">
                    <label for="complement">Complemento</label>
                    <input type="text" class="form-control" id="complement" value="<?= $get_user["complement"] ?>" name="complement">
                </div>

                <div class="form-group">
                    <label for="neighborhood">Bairro</label>
                    <input type="text" class="form-control" id="neighborhood" value="<?= $get_user["neighborhood"] ?>" name="neighborhood">
                </div>

                <div class="form-group">
                    <label for="city">Cidade</label>
                    <input type="text" class="form-control" id="city" value="<?= $get_user["city"] ?>" name="city">
                </div>

                <div class="form-group">
                    <label for="state">Estado</label>
                    <input type="text" class="form-control" id="state" value="<?= $get_user["state"] ?>" name="state" readonly>
                </div>

                <div class="form-group">
                    <label for="country">País</label>
                    <input type="text" class="form-control" id="country" value="<?= $get_user["country"] ?>" name="country" readonly>
                </div>

                <div class="form-group">
                    <label for="birth_date">Data de Nascimento</label>
                    <input type="date" class="form-control" id="birth_date" value="<?= $get_user["birth_date"] ?>" name="birth_date" required>
                </div>
                
                <div class="row">
                    <div class="col-6"><button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Salvar Alterações</button></div>
                    <div class="col-6" style="text-align: right;"><a class="btn btn-secondary" href="index.php?page=profile&action=users"><i class="fa-solid fa-xmark"></i> Cancelar</a></div>
                </div>

            </form>
        </div>

    <?php
    }
    ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Usuários</h2>
        <table id="myTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>nome</th>
                    <th>tipo</th>
                    <th>email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $get_users = $user->getAllWithouPagnation();

                foreach ($get_users as $item) :
                    $user_type = $item['user_type'] == "admin" ? "Administrador" : "Cliente";
                ?>
                    <tr>
                        <td><?php echo $item['user_id']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $user_type; ?></td>
                        </td>
                        <td><?php echo $item['email']; ?></td>
                        <td>
                            <a href="index.php?page=profile&action=users&user=<?php echo $item['user_id']; ?>" class="btn btn-warning btn-sm my-1 mx-1" target="_blank"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="index.php?page=profile&action=users&action2=delete&id=<?php echo $item['user_id']; ?>" class="btn btn-danger btn-sm my-1 mx-1"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ModalCadastrarUsuario" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalLongoExemplo">Cadastrar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./controllers/UserController.php?action=create" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="active" value="1">
                        <div class="form-group mb-3">
                            <label for="name">Nome do Usuario</label>
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
                            <label for="image">Imagem do Usuario</label>
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

                        <button type="submit" class="btn btn-primary">Criar Usuario</button>
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

<script>
    document.getElementById('postal_code').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');

        if (cep.length !== 8) {
            alert("CEP inválido. Digite os 8 números corretamente.");
            return;
        }

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert("CEP não encontrado.");
                    return;
                }

                document.getElementById('address').value = data.logradouro || '';
                document.getElementById('neighborhood').value = data.bairro || '';
                document.getElementById('city').value = data.localidade || '';
                document.getElementById('state').value = data.uf || '';
                document.getElementById('country').value = 'Brasil';
            })
            .catch(() => alert("Erro ao buscar o CEP. Tente novamente mais tarde."));
    });
</script>

<script>
    function mascaraCep(input) {
        let valor = input.value.replace(/\D/g, "");
        if (valor.length > 5) {
            input.value = valor.substring(0, 5) + '-' + valor.substring(5, 8);
        } else {
            input.value = valor;
        }
    }
</script>