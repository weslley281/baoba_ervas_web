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
        } else {
            self::$data = [
                'lastCommon' => 0,
                'lastPriority' => 0,
                'queueCommon' => [],
                'queuePriority' => [],
                'calledTickets' => []
            ];
            self::save();
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

        if (!empty(self::$data['queuePriority'])) {
            $ticket = array_shift(self::$data['queuePriority']);
        } elseif (!empty(self::$data['queueCommon'])) {
            $ticket = array_shift(self::$data['queueCommon']);
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
        //return self::$data['calledTickets'];

        // Pega os últimos 4 chamados, mantendo a ordem do mais recente para o mais antigo
        return array_slice(array_reverse(self::$data['calledTickets']), 0, 4);
    }

    public static function resetDay() {
        self::$data = [
            'lastCommon' => 0,
            'lastPriority' => 0,
            'queueCommon' => [],
            'queuePriority' => [],
            'calledTickets' => []
        ];
        self::save();
    }
}
?>
