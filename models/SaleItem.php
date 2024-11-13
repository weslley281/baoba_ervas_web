<?php
require_once __DIR__ . '/../config/db.php';

class SaleItem
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
            $stmt = $this->conn->prepare("INSERT INTO sales_item (sale_id, product_id, price, quantity, name)");

            $stmt->bind_param('iidds', $data["sale_id"], $data["product_id"], $data["price"], $data["quantity"], $data["name"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(array $data)
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE sales_item SET sale_id = ?, product_id = ?, price = ?, quantity = ?, name = ? WHERE sale_item_id = ?'
            );

            $stmt->bind_param('iiddsi', $data["sale_id"], $data["product_id"], $data["price"], $data["quantity"], $data["name"], $data["sale_item_id"]);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function delete($sale_item_id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM sales_item WHERE sale_item_id = ?');
            $stmt->bind_param('i', $sale_item_id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }
}
