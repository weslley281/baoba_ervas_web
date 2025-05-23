<?php
require_once '../models/Ticket.php';

header('Content-Type: application/json');
echo json_encode(array_reverse(Ticket::getCalledTickets()));
