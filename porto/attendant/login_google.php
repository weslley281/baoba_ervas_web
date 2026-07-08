<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db_main.php';

define('ID_CLIENT_GOOGLE', '495407143203-ikc2mrf5bh2nfppqdfpm34q9e7rdebhi.apps.googleusercontent.com');

if (isset($_POST['credential'])) {
    $id_token = $_POST['credential'];

    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
    
    $response = null;
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
    }
    
    if (!$response) {
        $context = stream_context_create([
            'http' => ['ignore_errors' => true, 'timeout' => 10],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);
        $response = @file_get_contents($url, false, $context);
    }

    $payload = json_decode($response, true);

    if (
        $payload &&
        isset($payload['email']) &&
        isset($payload['aud']) &&
        $payload['aud'] === ID_CLIENT_GOOGLE
    ) {
        $email = $payload['email'];

        $stmt = $conn_main->prepare("SELECT * FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            if ($user && ($user['user_type'] === 'admin' || $user['user_type'] === 'attendant')) {
                $_SESSION['attendant_logged'] = true;
                $_SESSION['attendant_user_id'] = $user['user_id'];
                $_SESSION['attendant_name'] = $user['name'];
                header("Location: index.php");
                exit();
            } else {
                header("Location: index.php?error=" . urlencode("Sua conta Google não está cadastrada ou não possui permissão de atendente no sistema principal."));
                exit();
            }
        }
    }
}

header("Location: index.php?error=" . urlencode("Falha na autenticação com o Google."));
exit();
