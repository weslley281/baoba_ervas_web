<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">Baobá Ervas e Cereais</h1>
        <p class="lead">Faça login na sua conta.</p>
    </div>
</div>

<form class="mt-5" action="./utils/authentication.php" method="POST">
    <div class="form-group my-2">
        <label for="email">Endereço de email</label>
        <input type="email" class="form-control" id="email" placeholder="Seu email" name="email">
    </div>
    <div class="form-group my-2">
        <label for="password">Senha</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Senha">
    </div>
    <div class="form-group my-2">
        <button type="submit" class="btn btn-success">Entrar</button>
    </div>
</form>