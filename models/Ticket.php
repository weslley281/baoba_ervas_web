<?php
date_default_timezone_set('America/Cuiaba');

class Ticket {
    private static $file;
    private static $data;

    private static function init() {
        $date = date('Y-m-d');
        self::$file = __DIR__ . "/data/tickets_$date.json";

        if (!file_exists(dirname(self::$file))) {
            mkdir(dirname(self::$file), 0777, true);
        }

        if (file_exists(self::$file)) {
            self::$data = json_decode(file_get_contents(self::$file), true);
            if (!is_array(self::$data)) {
                self::resetDay();
            } else {
                // Previne erros caso o arquivo já exista mas não contenha a nova chave
                if (!isset(self::$data['consecutivePriorityCalled'])) {
                    self::$data['consecutivePriorityCalled'] = 0;
                }
            }
        } else {
            self::resetDay();
        }
    }

    private static function save() {
        file_put_contents(self::$file, json_encode(self::$data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function generateTicket($type) {
        self::init();
        if ($type === 'prioritario') {
            self::$data['lastPriority']++;
            $ticketNumber = 'P' . str_pad(self::$data['lastPriority'], 3, '0', STR_PAD_LEFT);
            self::$data['queuePriority'][] = $ticketNumber;
        } else {
            self::$data['lastCommon']++;
            $ticketNumber = 'C' . str_pad(self::$data['lastCommon'], 3, '0', STR_PAD_LEFT);
            self::$data['queueCommon'][] = $ticketNumber;
        }

        self::save();
        return $ticketNumber;
    }

    public static function callNextTicket($atendente) {
        self::init();
        $ticket = null;

        $hasPriority = !empty(self::$data['queuePriority']);
        $hasCommon = !empty(self::$data['queueCommon']);

        if ($hasPriority && $hasCommon) {
            // Regra do revezamento 2:1 (chama no máximo 2 prioritários seguidos se houver comuns na fila)
            if (self::$data['consecutivePriorityCalled'] < 2) {
                $ticket = array_shift(self::$data['queuePriority']);
                self::$data['consecutivePriorityCalled']++;
            } else {
                $ticket = array_shift(self::$data['queueCommon']);
                self::$data['consecutivePriorityCalled'] = 0; // Reseta o contador de prioridade
            }
        } elseif ($hasPriority) {
            // Apenas prioritários na fila
            $ticket = array_shift(self::$data['queuePriority']);
            self::$data['consecutivePriorityCalled']++;
        } elseif ($hasCommon) {
            // Apenas comuns na fila
            $ticket = array_shift(self::$data['queueCommon']);
            self::$data['consecutivePriorityCalled'] = 0; // Zera pois chamou comum
        }

        if ($ticket) {
            $registro = [
                'ticket' => $ticket,
                'atendente' => $atendente,
                'hora' => date('H:i:s')
            ];
            self::$data['calledTickets'][] = $registro;
            self::save();
            return $registro;
        }

        return null;
    }

    public static function getCalledTickets() {
        self::init();
        // Pega os últimos 4 chamados, mantendo a ordem do mais recente para o mais antigo
        return array_slice(array_reverse(self::$data['calledTickets']), 0, 4);
    }

    public static function getQueueCounts() {
        self::init();
        return [
            'priority' => count(self::$data['queuePriority']),
            'common' => count(self::$data['queueCommon'])
        ];
    }

    public static function resetDay() {
        self::$data = [
            'lastCommon' => 0,
            'lastPriority' => 0,
            'queueCommon' => [],
            'queuePriority' => [],
            'calledTickets' => [],
            'consecutivePriorityCalled' => 0
        ];
        self::save();
    }
}
?>
