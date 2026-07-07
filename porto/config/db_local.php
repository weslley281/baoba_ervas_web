<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'u515961161_baoba_porto';
$password = 'B@oba2014';
$database = 'u515961161_baoba_porto';

$conn_local = new mysqli($host, $user, $password, $database);

if ($conn_local->connect_error) {
    die('Falha na conexão com o Banco de dados Local da Loja: ' . $conn_local->connect_error);
}
