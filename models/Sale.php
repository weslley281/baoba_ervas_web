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
            $stmt = $this->conn->prepare("
                INSERT INTO sales (customer_id, ticket_code, customer_name, phone, preferred_store, payment_method, total_price, delivery_type, delivery_address, situation)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                'isssssdsss',
                $data["customer_id"],
                $data["ticket_code"],
                $data["customer_name"],
                $data["phone"],
                $data["preferred_store"],
                $data["payment_method"],
                $data["total_price"],
                $data["delivery_type"],
                $data["delivery_address"],
                $data["situation"]
            );
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            }
            return false;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function getAllSales()
    {
        try {
            $result = $this->conn->query("SELECT * FROM sales ORDER BY createDate DESC");
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function getSaleById($sale_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM sales WHERE sale_id = ?");
            $stmt->bind_param('i', $sale_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getSaleItems($sale_id)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT si.*, p.slogan, p.path_image 
                FROM sales_item si
                LEFT JOIN products p ON si.product_id = p.product_id
                WHERE si.sale_id = ?
            ");
            $stmt->bind_param('i', $sale_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function updateSituation($sale_id, $situation)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE sales SET situation = ? WHERE sale_id = ?');
            $stmt->bind_param('si', $situation, $sale_id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function delete($sale_id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM sales WHERE sale_id = ?');
            $stmt->bind_param('i', $sale_id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }
}
