<?php
session_start();
require_once "../config/db.php";

// Verifica se o token ID foi enviado pelo botão do Google
if (isset($_POST['credential'])) {
    $id_token = $_POST['credential'];

    // Verifica o token com a API do Google
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $id_token;
    $response = file_get_contents($url);
    $payload = json_decode($response, true);

    // Verifica se o token é válido e se foi emitido para o Client ID correto
    if (
        isset($payload['email']) &&
        isset($payload['aud']) &&
        $payload['aud'] === ID_CLIENT_GOOGLE
    ) {
        $email = $payload['email'];
        $name = $payload['name'];

        // Consulta o usuário no banco
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            // Login direto
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['name'] = $user['name'];
        } else {
            // Cadastro automático
            $defaultType = 'client'; // Pode mudar conforme sua regra
            $stmt = $conn->prepare("INSERT INTO users (name, email, user_type) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $defaultType);
            $stmt->execute();
            $newUserId = $stmt->insert_id;

            $_SESSION['user_id'] = $newUserId;
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $defaultType;
            $_SESSION['name'] = $name;
        }

        header("Location: ../index.php?page=profile");
        exit();
    } else {
        // Token inválido ou não pertence ao app
        header("Location: ../index.php?page=login&action=fail");
        exit();
    }
} else {
    // Token não recebido
    header("Location: ../index.php?page=login&action=fail");
    exit();
}
