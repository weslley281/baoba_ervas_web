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
        $message = "Usuário bloqueado, tente novamente em " . gmdate("H:i:s", $timeLeft);
        echo "<script language='javascript'>window.alert('$message'); </script>";
        echo "<script language='javascript'>window.location='../views/login.php'; </script>";
        exit();
    }

    if (isset($_POST['email'], $_POST['password'])) {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $_POST['email']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($_POST['password'], $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['name'] = $user['name'];

            header("Location:  ../index.php?page=profile");

            exit;
        } else {
            // echo $_POST['password'] . "<br>";
            // echo $user['password'] . "<br>";
            // var_dump($user);
            // echo "<br>";
            // var_dump(password_verify($_POST['password'], $user['password']));
            // echo "<br>";
            // echo "PAREI AQUI 01";
            header("Location: ../index.php?page=login&action=fail");
        }
    } else {
        //echo "PAREI AQUI 02";
        header("Location: ../index.php?page=login&action=fail");
    }
}
