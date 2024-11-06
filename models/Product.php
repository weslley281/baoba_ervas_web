<?php
require_once __DIR__ . '/../config/db.php';

class Product
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
                'INSERT INTO products (name, slogan, category_id, description, path_image, price, discount, stock_quantity, reference, active)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $stmt->bind_param(
                'ssissdddsi',
                $data['name'],
                $data['slogan'],
                $data['category_id'],
                $data['description'],
                $data['path_image'],
                $data['price'],
                $data['discount'],
                $data['stock_quantity'],
                $data['reference'],
                $data['active']
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

    // public function getAll()
    // {
    //     try {
    //         $result = $this->conn->query('SELECT * FROM products');
    //         return $result->fetch_all(MYSQLI_ASSOC);
    //     } catch (mysqli_sql_exception $e) {
    //         error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
    //         return [];
    //     }
    // }

    public function getAll($page = 1, $perPage = 10)
    {
        // Limite de segurança para a quantidade de registros por página
        $perPage = max(1, min($perPage, 100)); // Limita o perPage entre 1 e 100

        try {
            // Calcula o offset com base na página atual
            $offset = ($page > 1 ? ($page - 1) * $perPage : 0);

            // Prepara a consulta com limite e offset para paginação
            $stmt = $this->conn->prepare('SELECT * FROM products LIMIT ? OFFSET ?');
            if (!$stmt) {
                throw new Exception('Falha ao preparar a consulta SQL.');
            }

            // Liga os parâmetros como inteiros
            $stmt->bind_param('ii', $perPage, $offset);

            // Executa a consulta
            $stmt->execute();
            $result = $stmt->get_result();

            // Retorna os resultados como um array associativo
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            // Registra o erro específico do MySQL em um arquivo de log
            error_log("Erro MySQL: " . $e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        } catch (Exception $e) {
            // Registra erros genéricos em um arquivo de log
            error_log("Erro geral: " . $e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function getTotalProducts()
    {
        try {
            // Prepara e executa a consulta para contar o total de produtos
            $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM products');
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // Retorna o número total de produtos
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }



    public function getById($product_id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = ?');
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
            $stmt = $this->conn->prepare('SELECT * FROM products WHERE slogan = ?');
            $stmt->bind_param('s', $slogan);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getDescription($param)
    {
        try {
            // Define a consulta SQL e o tipo do parâmetro com base no tipo do dado
            if (is_int($param)) {
                $sql = 'SELECT description FROM products WHERE product_id = ?';
                $type = 'i'; // Inteiro para product_id
            } else {
                $sql = 'SELECT description FROM products WHERE slogan = ?';
                $type = 's'; // String para slogan
            }

            // Prepara e executa a consulta
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($type, $param);
            $stmt->execute();

            // Retorna apenas a descrição do produto
            return $stmt->get_result()->fetch_assoc()['description'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getNameBySlogan($slogan)
    {
        try {
            $stmt = $this->conn->prepare('SELECT name FROM products WHERE slogan = ?');
            $stmt->bind_param('s', $slogan);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc()['name'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    // public function getByName($name)
    // {
    //     try {
    //         $name = '%' . $name . '%';
    //         $stmt = $this->conn->prepare('SELECT * FROM products WHERE name LIKE ?');

    //         $stmt->bind_param('s', $name);
    //         $stmt->execute();

    //         return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    //     } catch (mysqli_sql_exception $e) {
    //         error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
    //         return null;
    //     }
    // }

    public function getByName($name, $page = 1, $perPage = 10)
    {
        try {
            // Formata o nome para a busca com LIKE
            $name = '%' . $name . '%';

            // Calcula o offset com base na página atual
            $offset = ($page - 1) * $perPage;

            // Prepara a consulta com LIMIT e OFFSET para paginação
            $stmt = $this->conn->prepare('SELECT * FROM products WHERE name LIKE ? LIMIT ? OFFSET ?');
            $stmt->bind_param('sii', $name, $perPage, $offset);

            // Executa a consulta
            $stmt->execute();
            $result = $stmt->get_result();

            // Retorna os resultados como um array associativo
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            // Registra o erro em um arquivo de log
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }



    public function update(array $data, $product_id)
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE products SET name = ?, slogan = ?, category_id = ?, description = ?, path_image = ?, price = ?, discount = ?, stock_quantity = ?, reference = ?, active = ? WHERE product_id = ?'
            );

            $stmt->bind_param(
                'ssissdddsii',
                $data['name'],
                $data['slogan'],
                $data['category_id'],
                $data['description'],
                $data['path_image'],
                $data['price'],
                $data['discount'],
                $data['stock_quantity'],
                $data['reference'],
                $data['active'],
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
            $stmt = $this->conn->prepare('DELETE FROM products WHERE product_id = ?');
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
            $result = $this->conn->query('SELECT COUNT(*) as total FROM products');
            $row = $result->fetch_assoc();

            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }
}
