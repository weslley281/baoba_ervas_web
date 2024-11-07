<?php
require_once __DIR__ . '/../config/db.php';

class Sale
{
    private $conn;

    public function __construct($conn)
    {
        if ($conn === null) {
            throw new Exception("Conexão com o banco de dados não fornecida.");
        }
        $this->conn = $conn;
    }

    public function create(array $data)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO sales (customer_id, situation)");

            $stmt->bind_param('is', $data["customer_id"], $data["situation"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
