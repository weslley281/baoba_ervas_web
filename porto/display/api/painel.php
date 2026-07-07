<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../models/LocalTicket.php';

$ticketModel = new LocalTicket();
$chamados = $ticketModel->getCalledTickets();

// Retorna em ordem cronológica reversa (necessário ordenar do mais antigo para o mais recente para a fila de áudio do JS processar na ordem certa)
echo json_encode(array_reverse($chamados));
