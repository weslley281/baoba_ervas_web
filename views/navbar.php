<nav class="navbar navbar-expand-lg navbar bg-success px-2" data-bs-theme="dark">
    <a class="navbar-brand" href="index.php"><span class="text-white"><?php echo $page_title; ?></span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php"><span class="text-white"><i class="fa-solid fa-house"></i> Home</span></a>
            </li>

            <?php if (isset($_SESSION["user_id"])) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=profile"><span class="text-white"><i class="fa-solid fa-user"></i> Perfil</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=cart"><span class="text-white"><i class="fa-solid fa-hand-holding-dollar"></i> Carrinho</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="./utils/go_out.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sair</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=login"><span class="text-white"><i class="fa-solid fa-user"></i> Login</span></a>
                </li>
            <?php } ?>

            <li class="nav-item">
                <a class="nav-link" href="index.php?page=assessment"><span class="text-white"><i class="fa-solid fa-star"></i> Avalie a nossa loja</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?page=contact"><span class="text-white"><i class="fa-solid fa-envelope"></i> Contato</span></a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row bg-light">
        <div class="col-lg-4 col-md-6 col-sm-12 text-center rounded-3">
            <a href="index.php">
                <img id="logo" class="img-fluid py-1 px-1 rounded-3" src="./images/logo.png" style="border-radius: 1rem;" alt="">
            </a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 text-center">
            <nav class="navbar">
                <div class="container-fluid">
                    <form class="d-flex" action="index.php#products" method="GET">
                        <input class="form-control me-2" type="text" placeholder="Pesquisar" id="search" name="search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Pesquisar</button>
                    </form>
                </div>
            </nav>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 text-center">
            <a class="text-dark py-2 px-2" href="index.php?page=cart" target="_blank" rel="noopener noreferrer" title="Carrinho"><i class="fa-solid fa-cart-shopping fa-2x mx-1 my-2"></i></a>
            <a class="text-dark py-2 px-1" href="https://www.facebook.com/lojasbaobabrasil/" target="_blank" rel="noopener noreferrer" title="Facebook"><i class="fa-brands fa-square-facebook fa-2x mx-1 my-2"></i></a>
            <a class="text-dark py-2 px-1" href="https://www.instagram.com/baobabrasilervas/" target="_blank" rel="noopener noreferrer" title="Instagran"><i class="fa-brands fa-instagram fa-2x mx-1 my-2"></i></a>
            <a class="text-dark py-2 px-1" href="#" data-toggle="modal" data-target="#ModalWhatsapp" title="Whatsapp"><i class="fa-brands fa-whatsapp fa-2x mx-1 my-2"></i></a>
        </div>
    </div>
</div>