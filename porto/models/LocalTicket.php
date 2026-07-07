<?php
require_once __DIR__ . '/../config/db_local.php';

class LocalTicket {
    private $conn;

    public function __construct($conn = null) {
        global $conn_local;
        $this->conn = $conn ?? $conn_local;
    }

    public function generateTicket($type) {
        try {
            $prefix = ($type === 'prioritario') ? 'P' : 'C';
            $db_type = ($type === 'prioritario') ? 'priority' : 'common';

            // Busca a última senha do mesmo tipo criada hoje para obter a sequência
            $stmt = $this->conn->prepare("SELECT number FROM tickets WHERE DATE(created_at) = CURRENT_DATE() AND type = ? ORDER BY ticket_id DESC LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('s', $db_type);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                if ($res) {
                    $lastNumber = intval(substr($res['number'], 1));
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }
                $ticketNumber = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                // Insere a senha na fila de espera
                $stmt_insert = $this->conn->prepare("INSERT INTO tickets (number, type, status) VALUES (?, ?, 'waiting')");
                $stmt_insert->bind_param('ss', $ticketNumber, $db_type);
                $stmt_insert->execute();
                return $ticketNumber;
            }
            return null;
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    public function getQueueCounts() {
        try {
            $counts = ['priority' => 0, 'common' => 0];
            $stmt = $this->conn->prepare("SELECT type, COUNT(*) as qty FROM tickets WHERE DATE(created_at) = CURRENT_DATE() AND status = 'waiting' GROUP BY type");
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                foreach ($result as $row) {
                    if ($row['type'] === 'priority') {
                        $counts['priority'] = intval($row['qty']);
                    } else {
                        $counts['common'] = intval($row['qty']);
                    }
                }
            }
            return $counts;
        } catch (mysqli_sql_exception $e) {
            return ['priority' => 0, 'common' => 0];
        }
    }

    public function callNextTicket($attendant_id, $guiche_number) {
        try {
            // 1. Verifica se existem senhas prioritárias e comuns aguardando hoje
            $stmt_pri = $this->conn->prepare("SELECT ticket_id, number FROM tickets WHERE DATE(created_at) = CURRENT_DATE() AND type = 'priority' AND status = 'waiting' ORDER BY ticket_id ASC LIMIT 1");
            $stmt_pri->execute();
            $next_priority = $stmt_pri->get_result()->fetch_assoc();

            $stmt_com = $this->conn->prepare("SELECT ticket_id, number FROM tickets WHERE DATE(created_at) = CURRENT_DATE() AND type = 'common' AND status = 'waiting' ORDER BY ticket_id ASC LIMIT 1");
            $stmt_com->execute();
            $next_common = $stmt_com->get_result()->fetch_assoc();

            $selected_ticket = null;

            if ($next_priority && $next_common) {
                // Ambas as filas possuem senhas. Aplica o revezamento 2:1.
                // Conta quantas prioritárias foram chamadas consecutivamente hoje.
                $stmt_check = $this->conn->prepare("SELECT type FROM tickets WHERE DATE(created_at) = CURRENT_DATE() AND status != 'waiting' ORDER BY called_at DESC, ticket_id DESC LIMIT 3");
                $stmt_check->execute();
                $last_calls = $stmt_check->get_result()->fetch_all(MYSQLI_ASSOC);

                $consecutive_priority = 0;
                foreach ($last_calls as $c) {
                    if ($c['type'] === 'priority') {
                        $consecutive_priority++;
                    } else {
                        break;
                    }
                }

                if ($consecutive_priority < 2) {
                    $selected_ticket = $next_priority;
                } else {
                    $selected_ticket = $next_common;
                }
            } elseif ($next_priority) {
                // Apenas prioritários na fila
                $selected_ticket = $next_priority;
            } elseif ($next_common) {
                // Apenas comuns na fila
                $selected_ticket = $next_common;
            }

            if ($selected_ticket) {
                $ticket_id = $selected_ticket['ticket_id'];
                $called_time = date('Y-m-d H:i:s');
                
                $stmt_update = $this->conn->prepare("UPDATE tickets SET status = 'called', attendant_id = ?, guiche_number = ?, called_at = ? WHERE ticket_id = ?");
                $stmt_update->bind_param('iisi', $attendant_id, $guiche_number, $called_time, $ticket_id);
                $stmt_update->execute();

                return [
                    'ticket' => $selected_ticket['number'],
                    'atendente' => $guiche_number,
                    'hora' => date('H:i:s', strtotime($called_time))
                ];
            }
            return null;
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    public function getCalledTickets() {
        try {
            $stmt = $this->conn->prepare("SELECT number as ticket, guiche_number as atendente, DATE_FORMAT(called_at, '%H:%i:%s') as hora FROM tickets WHERE DATE(created_at) = CURRENT_DATE() AND status = 'called' ORDER BY called_at DESC LIMIT 4");
            if ($stmt) {
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
            return [];
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    public function resetDay($triggered_by = null) {
        try {
            // Insere um registro no log
            $today = date('Y-m-d');
            $stmt_log = $this->conn->prepare("INSERT IGNORE INTO daily_reset_log (reset_date, triggered_by) VALUES (?, ?)");
            $stmt_log->bind_param('si', $today, $triggered_by);
            $stmt_log->execute();

            // Limpa as senhas de hoje
            $stmt_clean = $this->conn->prepare("DELETE FROM tickets WHERE DATE(created_at) = CURRENT_DATE()");
            $stmt_clean->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }
}
