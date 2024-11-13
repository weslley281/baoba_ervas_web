<?php
require_once __DIR__ . '/db.php';

class CreateTables
{
    public static function createUsersTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            phone VARCHAR(100),
            email VARCHAR(191) UNIQUE,
            password VARCHAR(255),
            cpf VARCHAR(255),
            user_type ENUM('admin', 'client', 'attendant'),
            address VARCHAR(255),
            complement VARCHAR(255),
            country VARCHAR(100),
            state VARCHAR(100),
            city VARCHAR(100),
            neighborhood VARCHAR(100),
            postal_code VARCHAR(10),
            gender ENUM('masculine', 'feminine', 'non-binary', 'gender-fluid', 'transgender', 'agender', 'two-spirit', 'other', 'null'),
            birth_date DATE,
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'users' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'users': " . $conn->error;
        }
    }

    public static function createProductsTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS products (
            product_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            slogan VARCHAR(255),
            category_id INT,
            description TEXT,
            path_image VARCHAR(255),
            price DECIMAL(10, 2) NOT NULL,
            discount DECIMAL(10, 2) NOT NULL,
            stock_quantity DECIMAL(10, 2) NOT NULL,
            reference VARCHAR(255),
            active INT,
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'products' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'products': " . $conn->error;
        }
    }
    
    public static function createCategoriesTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS categories (
            category_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            slogan VARCHAR(255),
            description TEXT,
            path_image VARCHAR(255),
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'categories' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'categories': " . $conn->error;
        }
    }
    
    public static function createSalesTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS sales (
            sale_id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT,
            situation VARCHAR(255),
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'sales' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'sales': " . $conn->error;
        }
    }
    
    public static function createSalesItemTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS sales_item (
            sale_item_id INT AUTO_INCREMENT PRIMARY KEY,
            sale_id INT,
            product_id INT,
            name VARCHAR(255),
            price DECIMAL(10, 2) NOT NULL,
            quantity DECIMAL(10, 2) NOT NULL,
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'sales_item' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'sales_item': " . $conn->error;
        }
    }
}
