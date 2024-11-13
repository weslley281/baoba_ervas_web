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

    public function update(array $data)
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE sales SET situation = ? WHERE sale_id = ?'
            );

            $stmt->bind_param('si', $data["situation"], $data["sale_id"]);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }
}
