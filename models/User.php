<?php
require_once __DIR__ . '/../config/db.php';

class User
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
                'INSERT INTO users (name, phone, email, address, complement, country, state, city, neighborhood, postal_code, birth_date, password, user_type)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $stmt->bind_param(
                'sssssssssssss',
                $data['name'],
                $data['phone'],
                $data['email'],
                $data['address'],
                $data['complement'],
                $data['country'],
                $data['state'],
                $data['city'],
                $data['neighborhood'],
                $data['postal_code'],
                $data['birth_date'],
                $data['password'],
                $data['cpf'],
                $data['user_type']
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
            $result = $this->conn->query('SELECT * FROM users');
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function getById($user_id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE user_id = ?');
            $stmt->bind_param('i', $user_id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getByEmail($email)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function update(array $data, $user_id)
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE users SET name = ?, phone = ?, email = ?, address = ?, complement = ?, country = ?, state = ?, city = ?, neighborhood = ?, postal_code = ?, birth_date = ?, user_type = ? WHERE user_id = ?'
            );

            $stmt->bind_param(
                'ssssssssssssi',
                $data['name'],
                $data['phone'],
                $data['email'],
                $data['address'],
                $data['complement'],
                $data['country'],
                $data['state'],
                $data['city'],
                $data['neighborhood'],
                $data['postal_code'],
                $data['birth_date'],
                $data['user_type'],
                $user_id
            );

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function updateType($user_type, $user_id)
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE users SET user_type = ? WHERE id = ?'
            );

            $stmt->bind_param('si', $user_type, $user_id);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function delete($user_id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM users WHERE id = ?');
            $stmt->bind_param('i', $user_id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function countAll()
    {
        try {
            $result = $this->conn->query('SELECT COUNT(*) as total FROM users');
            $row = $result->fetch_assoc();

            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }
}
