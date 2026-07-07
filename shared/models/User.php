<?php
require_once __DIR__ . '/../config/db_shared.php';

class User {
    private $conn;

    public function __construct($conn = null) {
        global $conn_shared;
        $this->conn = $conn ?? $conn_shared;
    }

    public function getByEmail($email) {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = ?');
            if ($stmt) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                return $stmt->get_result()->fetch_assoc();
            }
            return null;
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    public function getById($user_id) {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE user_id = ?');
            if ($stmt) {
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                return $stmt->get_result()->fetch_assoc();
            }
            return null;
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    public function verifyLogin($email, $password) {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
