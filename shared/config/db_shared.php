<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'u515961161_baoba_shared';
$password = 'B@oba2014';
$database = 'u515961161_baoba_shared';

$conn_shared = new mysqli($host, $user, $password, $database);

if ($conn_shared->connect_error) {
    die('Falha na conexão com o Banco de dados Compartilhado: ' . $conn_shared->connect_error);
}
