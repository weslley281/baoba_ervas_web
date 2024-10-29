<?php
// Enviar senha temporária para o e-mail do usuário
function sendTemporaryPassword($email, $temporaryPassword)
{
    $subject = "Sua senha temporária";
    $message = "Olá, aqui está sua senha temporária: $temporaryPassword. Por favor, altere-a assim que possível.";
    $headers = "From: instituto@kenshydokan.org.br\r\n";

    return mail($email, $subject, $message, $headers); // Envia o e-mail
}
