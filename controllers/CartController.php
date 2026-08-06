<?php
session_start();
require_once "../config/db.php";
require_once "../models/Product.php";
require_once "../utils/cart.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

    switch ($action) {
        case 'add':
            $productModel = new Product($conn);
            $p = $productModel->getById($id);
            if (!$p || !$p['active']) {
                header("Location: ../index.php");
                exit;
            }

            // Calcula o preço final seguro no backend (evita adulteração do preço via POST)
            $weekly_promo_price = $productModel->getWeeklyPromotionalPrice($id);
            if ($weekly_promo_price !== null) {
                $final_price = $weekly_promo_price;
            } elseif ($p['discount'] > 0) {
                $final_price = $p['price'] * $p['discount'];
            } else {
                $final_price = $p['price'];
            }

            $data = [
                "name" => $p['name'],
                "path_image" => htmlspecialchars($_POST["path_image"] ?? $p['path_image'] ?? ''),
                "price" => $final_price,
                "amount" => isset($_POST["amount"]) ? intval($_POST["amount"]) : 1
            ];

            $redirect = "../index.php?page=product&slogan=" . urlencode($p['slogan']) . "&action=add";

            if (addCart($id, $data)) {
                header("Location: $redirect");
                exit;
            } else {
                echo "Quebrei";
                header("Location: ../index.php?page=profile&action=categories&action2=fail");
                exit;
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
