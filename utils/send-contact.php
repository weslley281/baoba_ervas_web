<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));

    if (!$nome || !$email || !$mensagem) {
        echo "<center><strong><h1>Dados inválidos</h1></strong></center>";
        echo "<script>";
        echo "setTimeout(function() { window.location.href = '../index.php?page=contact&action=invalid'; }, 2000);";
        echo "</script>";
        exit;
    }

    // Email settings
    $to = 'admbaobabrasil@gmail.com'; // Altere para o seu e-mail de destino
    $subject = 'Novo contato do site';
    $body = "Nome: $nome\nE-mail: $email\nMensagem:\n$mensagem";
    $headers = "From: $email\r\nReply-To: $email\r\n";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        // Email sent successfully
        echo "<center><strong><h1>Mensagem enviada com sucesso!</h1></strong></center>";
        echo "<script>";
        echo "setTimeout(function() { window.location.href = '../index.php?page=contact&action=success'; }, 2000);";
        echo "</script>";
    } else {
        // Email sending failed
        echo "<center><strong><h1>Falha ao enviar mensagem</h1></strong></center>";
        echo "<script>";
        echo "setTimeout(function() { window.location.href = '../index.php?page=contact&action=fail'; }, 2000);";
        echo "</script>";
    }
} else {
    // Invalid request method
    echo "<center><strong><h1>Método de requisição inválido</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=contact&action=invalid'; }, 2000);";
    echo "</script>";
    exit;
}
