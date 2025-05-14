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

    <!-- Div onde o botão do Google será exibido -->
    <div id="g_id_onload"
        data-client_id="<?php echo ID_CLIENT_GOOGLE; ?>"
        data-login_uri="./utils/login_google.php"
        data-auto_prompt="false">
    </div>

    <div class="g_id_signin"
        data-type="standard"
        data-shape="rectangular"
        data-theme="outline"
        data-text="signin_with"
        data-size="large"
        data-logo_alignment="left">
    </div>

    <!-- Script oficial da Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

</form>

<p>Não tem uma conta? <a href="index.php?page=register">Registrar-se</a></p>