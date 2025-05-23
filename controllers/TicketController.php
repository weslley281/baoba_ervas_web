<?php
require_once __DIR__ . '/../models/Ticket.php';
date_default_timezone_set('America/Cuiaba');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';
    switch ($action) {
        case 'generate':
            if (isset($_POST['tipo'])) {
                $tipo = $_POST['tipo'];
                if (!in_array($tipo, ['comum', 'prioritario'])) {
                    $erro = "Tipo inválido. Use 'comum' ou 'prioritario'.";
                    echo "<center><h1>$erro</h1></center>";
                    echo "<script>";
                    echo "setTimeout(function() { window.location.href = '../index.php?page=ticket'; }, 5000);";
                    echo "</script>";
                } else {
                    $tipo = htmlspecialchars($tipo);
                    $ticket = Ticket::generateTicket($tipo);
                    $ticket = htmlspecialchars($ticket);
                    echo "<center><h1>Seu ticket: <strong>$ticket</strong></h1></center>";
                    echo "<script>";
                    echo "setTimeout(function() { window.location.href = '../views/ticket/pdf_ticket.php?ticket=$ticket&tipo=$tipo'; }, 1000);";
                    echo "</script>";
                }
            } else {
                $erro = "Informe o tipo do ticket: comum ou prioritario.";
                echo "<center><h1>$erro</h1></center>";
                echo "<script>";
                echo "setTimeout(function() { window.location.href = '../index.php?page=ticket'; }, 5000);";
                echo "</script>";
            }
            break;

        case 'call':
            if (isset($_POST['atendente'])) {
                $atendente = $_POST['atendente'];
                $info = Ticket::callNextTicket($atendente);

                if ($info) {
                    $ticket = htmlspecialchars($info['ticket']);
                    $atendente = htmlspecialchars($info['atendente']);
                    $hora = htmlspecialchars($info['hora']);

                    echo "<center><h1>Chamando: <strong>Ticket $ticket</strong> no Atendente <strong>$atendente</strong> às <strong>$hora</strong></h1></center>";
                    echo "<script>";
                    echo "setTimeout(function() { window.location.href = '../index.php?page=ticket&action=call'; }, 5000);";
                    echo "</script>";
                } else {
                    $erro = "Nenhum cliente na fila.";
                    echo "<center><h1>$erro</h1></center>";
                    echo "<script>";
                    echo "setTimeout(function() { window.location.href = '../index.php?page=ticket&action=call'; }, 5000);";
                    echo "</script>";
                }
            } else {
                $erro = "Informe o número do atendente.";
                echo "<center><h1>$erro</h1></center>";
                echo "<script>";
                echo "setTimeout(function() { window.location.href = '../index.php?page=ticket&action=call'; }, 5000);";
                echo "</script>";
            }
            break;

        case 'panel':
            $chamados = Ticket::getCalledTickets();
            echo "<h1>Painel de Atendimento</h1>";
            foreach (array_reverse($chamados) as $c) {
                $ticket = htmlspecialchars($c['ticket']);
                $atendente = htmlspecialchars($c['atendente']);
                $hora = htmlspecialchars($c['hora']);

                echo "<p>Ticket <strong>$ticket</strong> — Atendente <strong>$atendente</strong> — Hora <strong>$hora</strong></p>";
            }

            if (empty($chamados)) {
                $erro = "Nenhum ticket chamado.";
                echo "<center><h1>$erro</h1></center>";
                echo "<script>";
                echo "setTimeout(function() { window.location.href = '../index.php?page=ticket'; }, 5000);";
                echo "</script>";
            }
            break;
    }
}

function generate()
{
    if (isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];
        if (!in_array($tipo, ['comum', 'prioritario'])) {
            echo "Tipo inválido. Use 'comum' ou 'prioritario'.";
            return;
        }

        $ticket = Ticket::generateTicket($tipo);
        echo "Seu ticket: <strong>$ticket</strong>";
    } else {
        echo "Informe o tipo do ticket: comum ou prioritario.";
    }
}

function call()
{
    if (isset($_GET['atendente'])) {
        $atendente = $_GET['atendente'];
        $info = Ticket::callNextTicket($atendente);

        if ($info) {
            echo "Chamando: <strong>Ticket {$info['ticket']}</strong> no Atendente <strong>{$info['atendente']}</strong>";
        } else {
            echo "Nenhum cliente na fila.";
        }
    } else {
        echo "Informe o número do atendente.";
    }
}

function panel()
{
    $chamados = Ticket::getCalledTickets();
    echo "<h1>Painel de Atendimento</h1>";
    foreach (array_reverse($chamados) as $c) {
        echo "<p>Ticket <strong>{$c['ticket']}</strong> — Atendente <strong>{$c['atendente']}</strong> — Hora <strong>{$c['hora']}</strong></p>";
    }
}
