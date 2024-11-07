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
                <a style="text-decoration: none;" href="index.php?page=profile&action=">
                    <li class="list-group-item list-group-item-action <?= $action == "" ? "active" : "" ?>">Perfil</li>
                </a>

                <a style="text-decoration: none;" href="index.php?page=cart">
                    <li class="list-group-item list-group-item-action <?= $page == "cart" ? "active" : "" ?>">Carrinho</li>
                </a>

                <?php
                //var_dump($_SESSION["user_type"]);
                if ($_SESSION["user_type"] == "admin") {
                ?>
                    <a style="text-decoration: none;" href="index.php?page=profile&action=products">
                        <li class="list-group-item list-group-item-action <?= $action == "products" ? "active" : "" ?>">Produtos</li>
                    </a>

                    <a style="text-decoration: none;" href="index.php?page=profile&action=categories">
                        <li class="list-group-item list-group-item-action <?= $action == "categories" ? "active" : "" ?>">Categorias</li>
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
                switch ($_GET["action2"]) {
                    case 'success':
                        echo renderAlert('success', 'Sucesso!', 'Produto cadastrado com sucesso.');
                        break;

                    case 'saved':
                        echo renderAlert('primary', 'Sucesso!', 'Produto editado com sucesso.');
                        break;

                    case 'invalid':
                        echo renderAlert('warning', 'Aviso!', 'Erro ao editar produto - contate o desenvolvedor do software.');
                        break;

                    case 'fail':
                        echo renderAlert('danger', 'Erro!', 'Erro ao editar produto - contate o desenvolvedor do software.');
                        break;
                }
                require_once "product.php";
            } elseif ($action == "categories") {
                require_once "categories.php";
            } else {
                switch ($action) {
                    case 'invalid':
                        echo renderAlert('warning', 'Aviso!', 'Erro ao editar usuário - contate o desenvolvedor do software.');
                        break;

                    case 'fail':
                        echo renderAlert('danger', 'Erro!', 'Erro ao editar usuário - contate o desenvolvedor do software.');
                        break;
                }
                require_once "edit_profile.php";
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