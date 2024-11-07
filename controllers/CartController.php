<?php
session_start();
require_once "../utils/cart.php";

if (isset($_SESSION["user_id"])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        function getCartData($post)
        {
            return [
                "name" => htmlspecialchars($post["name"] ?? ''),
                "path_image" => htmlspecialchars($post["path"] ?? ''),
                "price" => htmlspecialchars($post["price"] ?? 0),
                "amount" => htmlspecialchars($post["stock_quantity"] ?? 1),
            ];
        }

        switch ($action) {
            case 'add':
                $data = getCartData($_POST);
                $redirect = "../index.php?page=product&slogan=" . $_POST['slogan'] . "&action=add";

                if (addCart($id, $data)) {
                    header("Location: $redirect");
                } else {
                    echo "Quebrei";
                    header("Location: ../index.php?page=profile&action=categories&action2=fail");
                }

                break;

            default:
                echo "<center><strong><h1>Ação incorreta</h1></strong></center>";
                header("Location: ../index.php?page=profile&action=categories&action2=unknown");
                echo $_GET['action'];
                break;
        }
    } else {
        echo "<center><strong><h1>Requisição incorreta</h1></strong></center>";
        echo "<script>";
        echo "setTimeout(function() { window.location.href = '../index.php'; }, 3000);";
        echo "</script>";
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
