<div class="container d-flex align-items-center justify-content-center my-5" style="min-height: 60vh;">
    <div class="card shadow-lg border-0 p-4 p-md-5 w-100" style="max-width: 480px; border-radius: 16px; background-color: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;">
        <div class="text-center mb-4">
            <h2 class="text-success fw-bold mb-1"><i class="fa-solid fa-leaf"></i> Baobá Ervas</h2>
            <p class="text-muted small">Faça login para gerenciar seus pedidos ou atendimentos</p>
        </div>

        <form action="./utils/authentication.php" method="POST">
            <div class="form-group mb-3">
                <label for="email" class="form-label text-secondary fw-semibold mb-1">Endereço de e-mail</label>
                <input type="email" class="form-control" id="email" placeholder="nome@exemplo.com" name="email" required style="border-radius: 8px; padding: 0.6rem 0.8rem;">
            </div>
            
            <div class="form-group mb-4">
                <label for="password" class="form-label text-secondary fw-semibold mb-1">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Sua senha" required style="border-radius: 8px; padding: 0.6rem 0.8rem;">
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm mb-3" style="border-radius: 8px; background: linear-gradient(135deg, #198754, #157347); border: none;">Entrar</button>

            <div class="text-center text-muted small my-3">ou entre com sua conta Google</div>

            <!-- Div onde o botão do Google será exibido -->
            <div class="d-flex justify-content-center mb-3">
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
                    data-logo_alignment="left"
                    style="width: 100%;">
                </div>
            </div>

            <!-- Script oficial da Google Identity Services -->
            <script src="https://accounts.google.com/gsi/client" async defer></script>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted small">Não tem uma conta?</span> 
            <a href="index.php?page=register" class="text-success fw-semibold small text-decoration-none">Registrar-se</a>
        </div>
    </div>
</div>