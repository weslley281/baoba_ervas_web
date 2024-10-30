<?php
if (isset($_SESSION["user_id"])) {
    $get_user = $user->getById($_SESSION["user_id"]);
    $cpf = decrypt($get_user["cpf"], ENCRYPTION_KEY);
    $gender = [
        'masculine' => 'masculino',
        'feminine' => 'feminino',
        'non-binary' => 'não binário',
        'gender-fluid' => 'genero fluído',
        'transgender' => 'transgenero',
        'agender' => 'agênero',
        'two-spirit' => 'dois espirito',
        'other' => 'outro',
        'null' => 'prefiro não dizer'
    ]
?>
    <div class="row my-5">
        <div class="col-sm-12 col-lg-2 ">
            <ul class="list-group">
                <a style="text-decoration: none;" href="">
                    <li class="list-group-item list-group-item-action active">Perfil</li>
                </a>
                <a style="text-decoration: none;" href="">
                    <li class="list-group-item list-group-item-action">Carrinho</li>
                </a>
                <?php
                if ($_SESSION["user_type"] == "admin") {
                ?>
                    <a style="text-decoration: none;" href="">
                        <li class="list-group-item list-group-item-action">Produtos</li>
                    </a>
                    <a style="text-decoration: none;" href="">
                        <li class="list-group-item list-group-item-action">Usuários</li>
                    </a>
                    <a style="text-decoration: none;" href="">
                        <li class="list-group-item list-group-item-action">Pedidos</li>
                    </a>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="col-sm-12 col-lg-10">
            <?php
            if ($action == "cart") {
            ?>

            <?php
            } elseif ($action == "products") {
            ?>

            <?php
            } else {
                switch ($action) {
                    case 'invalid':
                        echo renderAlert('warning', 'Aviso!', 'Erro ao editar usuário - contate o desenvolvedor do software.');
                        break;

                    case 'fail':
                        echo renderAlert('danger', 'Erro!', 'Erro ao editar usuário - contate o desenvolvedor do software.');
                        break;
                }
            ?>
                <form action="./controllers/UserController.php?action=update" method="POST">
                    <input type="hidden" id="user_id" value="<?= $get_user["user_id"] ?>" name="user_id">

                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" value="<?= $get_user["name"] ?>" name="name">
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefone:</label>
                        <input type="text" class="form-control" id="phone" value="<?= $get_user["phone"] ?>" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" class="form-control" id="cpf" value="<?= $cpf ?>" name="cpf">
                    </div>

                    <div class="form-group">
                        <label for="email">Endereço de email</label>
                        <input type="email" class="form-control" id="email" value="<?= $get_user["email"] ?>" name="email">
                    </div>

                    <div class="form-group">
                        <label for="postal_code">Código Postal</label>
                        <input type="text" class="form-control" id="postal_code" value="<?= $get_user["postal_code"] ?>" name="postal_code">
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
                        <label for="gender">Gênero</label>
                        <select class="form-control" name="gender" id="gender">
                            <!-- Exibir o gênero atual como primeira opção -->
                            <option value="<?= $get_user["gender"] ?>"><?= $gender[$get_user["gender"]] ?></option>
                            <?php
                            // Exibir as demais opções de gênero, excluindo a já selecionada
                            foreach ($gender as $key => $value) {
                                if ($key != $get_user["gender"]) {
                                    echo "<option value=\"$key\">$value</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="birth_date">Data de Nascimento</label>
                        <input type="date" class="form-control" id="birth_date" value="<?= $get_user["birth_date"] ?>" name="birth_date">
                    </div>

                    <button type="submit" class="btn btn-info">Editar</button>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
<?php
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = './index.php?page=login'; }, 3000);";
    echo "</script>";
}
?>