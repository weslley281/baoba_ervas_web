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
                'ssss',
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

    public function getById($category_id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM categories WHERE category_id = ?');
            $stmt->bind_param('i', $category_id);
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

    public function getNameById($category_id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT name FROM categories WHERE category_id = ?');
            $stmt->bind_param('i', $category_id);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();

            if ($result) {
                return $result["name"];
            } else {
                return null;
            }
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getIDBySlogan($slogan)
{
    try {
        $stmt = $this->conn->prepare('SELECT category_id FROM categories WHERE slogan = ?');
        $stmt->bind_param('s', $slogan);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            return $result["category_id"];
        } else {
            return null;
        }
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


    public function update(array $data, $category_id)
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE categories SET name = ?, slogan = ?, description = ?, path_image = ? WHERE category_id = ?'
            );

            $stmt->bind_param(
                'ssssi',
                $data['name'],
                $data['slogan'],
                $data['description'],
                $data['path_image'],
                $category_id
            );

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function delete($category_id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM categories WHERE category_id = ?');
            $stmt->bind_param('i', $category_id);
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
