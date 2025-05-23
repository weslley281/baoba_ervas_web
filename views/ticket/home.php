<?php
if (isset($_SESSION["user_id"]) && isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "admin") {
    //$get_user = $user->getById($_SESSION["user_id"]);
?>
    <div class="row my-5">

        <div class="col-12 mb-3 d-lg-none">
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
                <a class="navbar-brand" href="#">Menu</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Alternar navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mobileMenu">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?= $action == "" ? "active" : "" ?>" href="index.php?page=ticket&action=">Chamar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == "cart" ? "active" : "" ?>" href="index.php?page=cart">Carrinho</a>
                        </li>
                        <?php if ($_SESSION["user_type"] == "admin") { ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $action == "products" ? "active" : "" ?>" href="index.php?page=ticket&action=products">Produtos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $action == "categories" ? "active" : "" ?>" href="index.php?page=ticket&action=categories">Categorias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Usuários</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Pedidos</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="col-lg-2 d-none d-lg-block">
            <ul class="list-group">
                <?php if ($_SESSION["user_type"] == "admin") { ?>

                    <a style="text-decoration: none;" href="index.php?page=ticket&action=">
                        <li class="list-group-item list-group-item-action <?= $action == "" ? "active" : "" ?>">Gerador</li>
                    </a>

                    <a style="text-decoration: none;" href="index.php?page=ticket&action=call">
                        <li class="list-group-item list-group-item-action <?= $action == "call" ? "active" : "" ?>">Chamador</li>
                    </a>

                    <a style="text-decoration: none;" href="index.php?page=ticket&action=panel">
                        <li class="list-group-item list-group-item-action <?= $action == "panel" ? "active" : "" ?>">Painel</li>
                    </a>

                <?php } ?>
            </ul>
        </div>


        <div class="col-sm-12 col-lg-10">
            <?php
            if ($action == "cart") {

            ?>

            <?php
            } elseif ($action == "products") {
                if (isset($_GET["action2"])) {
                    switch ($_GET["action2"]) {
                        case 'success':
                            echo renderAlert('success', 'Sucesso!', 'Produto cadastrado com sucesso.');
                            break;

                        case 'saved':
                            echo renderAlert('primary', 'Sucesso!', 'Produto editado com sucesso.');
                            break;

                        case 'invalid':
                            echo renderAlert('warning', 'Aviso!', 'Erro ao editar produto - verifique se todos os campos foram preenchidos.');
                            break;

                        case 'fail':
                            echo renderAlert('danger', 'Erro!', 'Erro ao editar produto - contate o verifique se todos os campos foram preenchidos.');
                            break;

                        case 'deleted':
                            echo renderAlert('success', 'Sucesso!', 'Produto excluído com sucesso.');
                            break;
                    }
                }
                require_once "product.php";
            } elseif ($action == "call") {
                require_once "call.php";
            } elseif ($action == "panel") {
                require_once "panel.php";
            } else {
                switch ($action) {
                    case 'invalid':
                        echo renderAlert('warning', 'Aviso!', 'Erro ao editar usuário - verifique se todos os campos foram preenchidos.');
                        break;

                    case 'fail':
                        echo renderAlert('danger', 'Erro!', 'Erro ao editar usuário - verifique se todos os campos foram preenchidos.');
                        break;
                }
                require_once "generate.php";
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