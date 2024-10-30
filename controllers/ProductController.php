<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['user_type'] == "admin") {
    require_once __DIR__ . '/../models/product.php';
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../utils/generateRandomPassword.php';
    require_once __DIR__ . '/../utils/openssl.php';

    $product = new product($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        function getproductData($post)
        {

            return [
                "name" => htmlspecialchars($post["name"] ?? ''),
                "slogan" => htmlspecialchars($post["slogan"] ?? ''),
                "description" => htmlspecialchars($post["description"] ?? ''),
                "path_image" => htmlspecialchars($post["path_image"] ?? ''),
                "price" => htmlspecialchars($post["price"] ?? ''),
                "discount" => htmlspecialchars($post["discount"] ?? ''),
                "stock_quantity" => htmlspecialchars($post["stock_quantity"] ?? ''),
                "reference" => htmlspecialchars($post["reference"] ?? '')
            ];
        }

        switch ($action) {
            case 'create':
                $data = getproductData($_POST);

                if ($product->create($data)) {
                    header("Location: ../index.php?page=products&action=success");
                } else {
                    echo $product->create($data);
                    header("Location: ../index.php?page=products&action=fail");
                }

                break;

            case 'update': // Atualiza um usuário existente
                if ($id === null) {
                    header("Location: ../index.php?page=products&action=invalid");
                    exit;
                }
                $data = getproductData($_POST);
                if ($product->update($data, $id)) {
                    header("Location: ../index.php?page=products&action=saved");
                } else {
                    header("Location: ../index.php?page=products&action=fail");
                }
                break;

            case 'delete': // Deleta um usuário pelo ID
                if ($id === null) {
                    header("Location: ../index.php?page=products&action=invalid");
                    exit;
                }
                if ($product->delete($id)) {
                    header("Location: ../index.php?page=products&action=deleted");
                } else {
                    header("Location: ../index.php?page=products&action=fail");
                }
                break;

            default: // Se nenhuma ação for definida
                echo "<center><strong><h1>Ação incorreta</h1></strong></center>";
                header("Location: ../index.php?page=products&action=unknown");
                echo $_GET['action'];
                break;
        }
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
