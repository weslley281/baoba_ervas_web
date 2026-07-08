<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'u515961161_baoba_ervas';
$password = 'B@oba2014';
$database = 'u515961161_baoba_ervas';

$conn_main = new mysqli($host, $user, $password, $database);

if ($conn_main->connect_error) {
    die('Falha na conexão com o Banco de dados Principal: ' . $conn_main->connect_error);
}
