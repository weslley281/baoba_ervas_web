<?php
session_start(); // Inicia a sessão para armazenar dados de sessão
require_once "../config/db.php";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $limitAttempts = 3;

    if (!isset($_SESSION['attempts'])) {
        $_SESSION['attempts'] = 0;
    }

    if (isset($_SESSION['block']) && $_SESSION['block'] > time()) {
        $timeLeft = $_SESSION['block'] - time();
        $message = "Usuario bloqueado por excesso de tentativas. Tente novamente em " . gmdate("H:i:s", $timeLeft);
        echo "<script language='javascript'>window.alert('$message'); </script>";
        echo "<script language='javascript'>window.location='../index.php?page=login'; </script>";
        exit();
    }

    if (isset($_POST['email'], $_POST['password'])) {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $_POST['email']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['attempts'] = 0;
            if (isset($_SESSION['block'])) {
                unset($_SESSION['block']);
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['name'] = $user['name'];

            header("Location: ../index.php?page=profile");
            exit;
        } else {
            $_SESSION['attempts'] = ($_SESSION['attempts'] ?? 0) + 1;
            if ($_SESSION['attempts'] >= $limitAttempts) {
                $_SESSION['block'] = time() + 300; // Bloqueia por 5 minutos (300 segundos)
                $_SESSION['attempts'] = 0;
            }
            header("Location: ../index.php?page=login&action=fail");
            exit;
        }
    } else {
        header("Location: ../index.php?page=login&action=fail");
        exit;
    }
}
