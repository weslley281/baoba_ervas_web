<?php
if (isset($_SESSION["user_id"])) {
?>
    <div class="row my-5">
        <div class="col-sm-12 col-lg-2 ">
            <ul class="list-group">
                <a href="">
                    <li class="list-group-item active">Perfil</li>
                </a>
                <a href="">
                    <li class="list-group-item">Carrinho</li>
                </a>
                <?php
                if ($_SESSION["user_type"] == "admin") {
                ?>
                    <a href="">
                        <li class="list-group-item">Produtos</li>
                    </a>
                    <a href="">
                        <li class="list-group-item">Usuários</li>
                    </a>
                    <a href="">
                        <li class="list-group-item">Pedidos</li>
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
            ?>
                <form action="./utils/authentication.php" method="POST">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" placeholder="Seu email" name="name">
                    </div>

                    <div class="form-group">
                        <label for="email">Endereço de email</label>
                        <input type="email" class="form-control" id="email" placeholder="Seu email" name="email">
                    </div>

                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Senha">
                    </div>
                    <button type="submit" class="btn btn-primary">Entrar</button>
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