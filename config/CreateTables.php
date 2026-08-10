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
            user_type ENUM('admin', 'client', 'attendant'),
            address VARCHAR(255),
            complement VARCHAR(255),
            country VARCHAR(100),
            state VARCHAR(100),
            city VARCHAR(100),
            neighborhood VARCHAR(100),
            postal_code VARCHAR(10),
            birth_date DATE,
            cpf VARCHAR(255),
            gender VARCHAR(50),
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        if ($conn->query($sql) === true) {
            // Verificar e adicionar colunas cpf e gender se não existirem
            $checkCpf = $conn->query("SHOW COLUMNS FROM users LIKE 'cpf'");
            if ($checkCpf && $checkCpf->num_rows == 0) {
                $conn->query("ALTER TABLE users ADD COLUMN cpf VARCHAR(255)");
            }
            $checkGender = $conn->query("SHOW COLUMNS FROM users LIKE 'gender'");
            if ($checkGender && $checkGender->num_rows == 0) {
                $conn->query("ALTER TABLE users ADD COLUMN gender VARCHAR(50)");
            }
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
        try {
            $sql = "
            CREATE TABLE IF NOT EXISTS sales (
                sale_id INT AUTO_INCREMENT PRIMARY KEY,
                customer_id INT NULL,
                ticket_code VARCHAR(20) NOT NULL,
                customer_name VARCHAR(255) NOT NULL,
                phone VARCHAR(30) NOT NULL,
                preferred_store VARCHAR(50) NOT NULL,
                payment_method VARCHAR(50) NOT NULL,
                total_price DECIMAL(10, 2) NOT NULL,
                delivery_type VARCHAR(50) NOT NULL,
                delivery_address TEXT NULL,
                situation VARCHAR(50) DEFAULT 'Pendente',
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
            ";
            $conn->query($sql);
        } catch (Throwable $e) {
            error_log("Erro ao inicializar tabela sales: " . $e->getMessage());
        }
    }
    
    public static function createSalesItemTable($conn)
    {
        try {
            $sql = "
            CREATE TABLE IF NOT EXISTS sales_item (
                sale_item_id INT AUTO_INCREMENT PRIMARY KEY,
                sale_id INT NOT NULL,
                product_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                quantity INT NOT NULL,
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (sale_id) REFERENCES sales(sale_id) ON DELETE CASCADE
            );
            ";
            $conn->query($sql);
        } catch (Throwable $e) {
            error_log("Erro ao inicializar tabela sales_item: " . $e->getMessage());
        }
    }

    public static function createProductRatingsTable($conn)
    {
        try {
            $sql = "
            CREATE TABLE IF NOT EXISTS product_ratings (
                rating_id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                user_id INT NOT NULL,
                rating INT NOT NULL,
                comment TEXT NULL,
                admin_reply TEXT NULL,
                replyDate TIMESTAMP NULL,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                KEY idx_product (product_id),
                KEY idx_user (user_id),
                UNIQUE KEY unique_user_product (user_id, product_id)
            );
            ";
            $conn->query($sql);

            // Verificar e adicionar colunas se a tabela já existia sem elas de forma segura
            $checkComment = $conn->query("SHOW COLUMNS FROM product_ratings LIKE 'comment'");
            if ($checkComment && $checkComment->num_rows == 0) {
                @$conn->query("ALTER TABLE product_ratings ADD COLUMN comment TEXT NULL");
            }
            
            $checkReply = $conn->query("SHOW COLUMNS FROM product_ratings LIKE 'admin_reply'");
            if ($checkReply && $checkReply->num_rows == 0) {
                @$conn->query("ALTER TABLE product_ratings ADD COLUMN admin_reply TEXT NULL");
            }
            
            $checkReplyDate = $conn->query("SHOW COLUMNS FROM product_ratings LIKE 'replyDate'");
            if ($checkReplyDate && $checkReplyDate->num_rows == 0) {
                @$conn->query("ALTER TABLE product_ratings ADD COLUMN replyDate TIMESTAMP NULL");
            }
        } catch (Throwable $e) {
            // Silencia qualquer exceção de banco de dados para evitar travar a inicialização do site (HTTP 500)
            error_log("Erro ao inicializar tabela product_ratings: " . $e->getMessage());
        }
    }

    public static function createWeeklyPromotionsTable($conn)
    {
        try {
            $sql = "
            CREATE TABLE IF NOT EXISTS weekly_promotions (
                promotion_id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                weekday INT NOT NULL,
                promotional_price DECIMAL(10,2) NOT NULL,
                active TINYINT(1) DEFAULT 1,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                KEY idx_product (product_id),
                KEY idx_weekday (weekday),
                UNIQUE KEY unique_product_weekday (product_id, weekday)
            );
            ";
            $conn->query($sql);
        } catch (Throwable $e) {
            error_log("Erro ao criar tabela weekly_promotions: " . $e->getMessage());
        }
    }
}
