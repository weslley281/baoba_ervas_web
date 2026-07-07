<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../shared/models/User.php';

define('ID_CLIENT_GOOGLE', '495407143203-ikc2mrf5bh2nfppqdfpm34q9e7rdebhi.apps.googleusercontent.com');

if (isset($_POST['credential'])) {
    $id_token = $_POST['credential'];

    // Verifica o token com a API do Google
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
    
    // Configura o stream context para ignorar erros e não falhar em redirecionamento/erros HTTPS
    $context = stream_context_create([
        'http' => ['ignore_errors' => true]
    ]);
    
    $response = file_get_contents($url, false, $context);
    $payload = json_decode($response, true);

    if (
        isset($payload['email']) &&
        isset($payload['aud']) &&
        $payload['aud'] === ID_CLIENT_GOOGLE
    ) {
        $email = $payload['email'];

        $userModel = new User();
        $user = $userModel->getByEmail($email);

        if ($user && ($user['user_type'] === 'admin' || $user['user_type'] === 'attendant')) {
            $_SESSION['attendant_logged'] = true;
            $_SESSION['attendant_user_id'] = $user['user_id'];
            $_SESSION['attendant_name'] = $user['name'];
            header("Location: index.php");
            exit();
        } else {
            // Usuário não cadastrado ou não é atendente/admin
            header("Location: index.php?error=" . urlencode("Sua conta Google não está cadastrada ou não possui permissão de atendente."));
            exit();
        }
    }
}

header("Location: index.php?error=" . urlencode("Falha na autenticação com o Google."));
exit();
