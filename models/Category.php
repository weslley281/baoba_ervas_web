<?php
require_once __DIR__ . '/../config/db.php';

class Category
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
            $stmt = $this->conn->prepare(
                'INSERT INTO categories (name, slogan, description, path_image)
                 VALUES (?, ?, ?, ?)'
            );

            $stmt->bind_param(
                'ssssddds',
                $data['name'],
                $data['slogan'],
                $data['description'],
                $data['path_image']
            );

            $stmt->execute();
            //echo "Deu certo";
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            //echo "Deu merda";
            return false;
        }
    }

    public function getAll()
    {
        try {
            $result = $this->conn->query('SELECT * FROM categories');
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function getById($product_id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM categories WHERE category_id = ?');
            $stmt->bind_param('i', $product_id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getBySlogan($slogan)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM categories WHERE slogan = ?');
            $stmt->bind_param('s', $slogan);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getByName($name)
    {
        try {
            $name = '%' . $name . '%';
            $stmt = $this->conn->prepare('SELECT * FROM categories WHERE name LIKE ?');

            $stmt->bind_param('s', $name);
            $stmt->execute();

            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }


    public function update(array $data, $product_id)
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE category SET name = ?, slogan = ?, description = ?, path_image = ? WHERE product_id = ?'
            );

            $stmt->bind_param(
                'ssssi',
                $data['name'],
                $data['slogan'],
                $data['description'],
                $data['path_image'],
                $product_id
            );

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function delete($product_id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM categories WHERE product_id = ?');
            $stmt->bind_param('i', $product_id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function countAll()
    {
        try {
            $result = $this->conn->query('SELECT COUNT(*) as total FROM categories');
            $row = $result->fetch_assoc();

            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }
}
