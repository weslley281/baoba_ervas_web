<nav class="navbar navbar-expand-lg navbar navbar-success bg-success px-2">
    <a class="navbar-brand" href="index.php"><span class="text-white"><?php echo $page_title; ?></span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php"><span class="text-white"><i class="fa-solid fa-house"></i> Home</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=profile"><span class="text-white"><i class="fa-solid fa-user"></i> Perfil</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=financial"><span class="text-white"><i class="fa-solid fa-hand-holding-dollar"></i> Carrinho</span></a>
            </li>
            <?php if (isset($_SESSION["user_id"])) { ?>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="./utils/go_out.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sair</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>