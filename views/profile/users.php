<!-- Lista de Usuarios -->
<?php
if (isset($_SESSION["user_id"]) && $_SESSION['user_type'] == "admin") {
    $get_categories = $category->getAll();
    if (isset($_GET["user"])) {
        $get_user = $user->getById($_GET["user"]);

?>
        <!-- Formulário de Edição de Usuario -->
        <div class="container mt-5">
            <h2 class="text-center mb-4">Editar Usuário</h2>
            <form action="./controllers/UserController.php?action=update" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $get_user['user_id']; ?>">

                <div class="form-group mb-3">
                    <label for="name">Nome do Usuário</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($get_user['name']); ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Endereço de e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($get_user['email']); ?>" readonly required>
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
                    <h5 class="modal-title" id="TituloModalLongoExemplo">Cadastrar Usuário</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="./controllers/UserController.php?action=create" method="POST" onsubmit="return validateRegPasswords()">
                        <div class="form-group mb-3">
                            <label for="reg_name">Nome</label>
                            <input type="text" class="form-control" id="reg_name" name="name" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="reg_phone">Telefone</label>
                            <input type="tel" class="form-control" id="reg_phone" name="phone" placeholder="(XX) XXXXX-XXXX" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_cpf">CPF</label>
                            <input type="text" class="form-control" id="reg_cpf" name="cpf" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_email">E-mail</label>
                            <input type="email" class="form-control" id="reg_email" name="email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_postal_code">CEP</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="reg_postal_code" maxlength="9" name="postal_code" oninput="mascaraCep(this)" placeholder="00000-000">
                                <button type="button" class="btn btn-secondary" onclick="regBuscarCep()">Buscar</button>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_address">Endereço</label>
                            <input type="text" class="form-control" id="reg_address" name="address">
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_complement">Complemento</label>
                            <input type="text" class="form-control" id="reg_complement" name="complement">
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_neighborhood">Bairro</label>
                            <input type="text" class="form-control" id="reg_neighborhood" name="neighborhood">
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_city">Cidade</label>
                            <input type="text" class="form-control" id="reg_city" name="city">
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_state">Estado</label>
                            <input type="text" class="form-control" id="reg_state" name="state" value="MT" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_country">País</label>
                            <input type="text" class="form-control" id="reg_country" name="country" value="Brasil" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_gender">Gênero</label>
                            <select class="form-control" name="gender" id="reg_gender">
                                <option value="masculine">masculino</option>
                                <option value="feminine">feminino</option>
                                <option value="non-binary">não binário</option>
                                <option value="gender-fluid">genero fluído</option>
                                <option value="transgender">transgenero</option>
                                <option value="agender">agênero</option>
                                <option value="two-spirit">dois espirito</option>
                                <option value="other">outro</option>
                                <option value="null">prefiro não dizer</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_birth_date">Data de Nascimento</label>
                            <input type="date" class="form-control" id="reg_birth_date" name="birth_date">
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_password">Senha</label>
                            <input type="password" class="form-control" id="reg_password" name="password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reg_confirmPassword">Confirmar Senha</label>
                            <input type="password" class="form-control" id="reg_confirmPassword" name="confirmPassword" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Criar Usuário</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Deletar Usuário -->
    <?php
    if (isset($_GET["action2"]) && $_GET["action2"] == "delete") {
        $get_user_to_delete = $user->getById($_GET["id"]);
        if ($get_user_to_delete) {
    ?>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    $('#ModalDeletarUsuario').modal('show');
                });
            </script>
            <div class="modal fade" id="ModalDeletarUsuario" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoDeletarUsuario" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="TituloModalLongoDeletarUsuario">Deletar Usuário</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Você tem certeza que deseja deletar o usuário <strong><?php echo htmlspecialchars($get_user_to_delete['name']); ?></strong>?</p>
                            <p class="text-danger"><strong>Esta ação é irreversível!</strong></p>
                            <form action="./controllers/UserController.php?action=delete" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $get_user_to_delete['user_id']; ?>">
                                <button type="submit" class="btn btn-danger">Deletar Usuário</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
<?php
} else {
    echo "<center><strong><h1>Você não tem permissão para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = 'index.php?page=login'; }, 3000);";
    echo "</script>";
    exit;
}
?>

<script>
    // Validação de senha no cadastro
    function validateRegPasswords() {
        const password = document.getElementById('reg_password').value;
        const confirmPassword = document.getElementById('reg_confirmPassword').value;
        if (password !== confirmPassword) {
            alert("As senhas não coincidem!");
            return false;
        }
        return true;
    }

    // Máscara de CEP
    function mascaraCep(input) {
        let valor = input.value.replace(/\D/g, "");
        if (valor.length > 5) {
            input.value = valor.substring(0, 5) + '-' + valor.substring(5, 8);
        } else {
            input.value = valor;
        }
    }

    // Gatilhos manuais para buscar CEP
    function buscarCep() {
        const input = document.getElementById('postal_code');
        if (input) input.dispatchEvent(new Event('blur'));
    }

    function regBuscarCep() {
        const input = document.getElementById('reg_postal_code');
        if (input) input.dispatchEvent(new Event('blur'));
    }

    // Event listener para CEP no formulário de Edição
    document.addEventListener("DOMContentLoaded", function() {
        const editCepInput = document.getElementById('postal_code');
        if (editCepInput) {
            editCepInput.addEventListener('blur', function() {
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
        }

        // Event listener para CEP no formulário de Cadastro
        const regCepInput = document.getElementById('reg_postal_code');
        if (regCepInput) {
            regCepInput.addEventListener('blur', function() {
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
                        document.getElementById('reg_address').value = data.logradouro || '';
                        document.getElementById('reg_neighborhood').value = data.bairro || '';
                        document.getElementById('reg_city').value = data.localidade || '';
                        document.getElementById('reg_state').value = data.uf || '';
                        document.getElementById('reg_country').value = 'Brasil';
                    })
                    .catch(() => alert("Erro ao buscar o CEP. Tente novamente mais tarde."));
            });
        }
    });
</script>