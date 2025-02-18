<?php
// Verificar todos os campos recebidos
$email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
$phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : '';
$rating = isset($_POST["rating"]) ? trim($_POST["rating"]) : '';
$assessment = isset($_POST["assessment"]) ? trim($_POST["assessment"]) : '';

if (empty($email) || empty($phone) || empty($rating) || empty($assessment)) {
    echo "<center><strong><h1>Todos os campos são obrigatórios</h1></strong></center>";
    echo "<script>setTimeout(function() { window.location.href = '../index.php?page=assessment'; }, 3000);</script>";
    exit;
}

function sendAssessmentEmail($email, $phone, $rating, $assessment)
{
    $subject = "Nova Avaliação Recebida";
    $message = "Você recebeu uma nova avaliação:\n\n" .
        "Email: $email\n" .
        "Telefone: $phone\n" .
        "Avaliação (1 a 5): $rating\n" .
        "Comentário:\n$assessment\n";
    $headers = "From: admbaobabrasil@gmail.com\r\n" .
        "Reply-To: $email\r\n" .
        "Content-Type: text/plain; charset=UTF-8\r\n";
    return mail("admbaobabrasil@gmail.com", $subject, $message, $headers);
}

$sup = sendAssessmentEmail($email, $phone, $rating, $assessment);

if ($sup) {
    echo "<center><strong><h1>Sucesso</h1></strong></center>";
    echo "<script>setTimeout(function() { window.location.href = '../index.php'; }, 1000);</script>";
} else {
    echo "<center><strong><h1>Erro ao enviar a avaliação</h1></strong></center>";
    echo "<script>setTimeout(function() { window.location.href = '../index.php?page=assessment'; }, 3000);</script>";
}
