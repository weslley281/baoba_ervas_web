<?php
session_start();
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../index.php?page=login");
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$productModel = new Product($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'save') {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $weekday = isset($_POST['weekday']) ? intval($_POST['weekday']) : 0;
        $promotional_price = isset($_POST['promotional_price']) ? floatval($_POST['promotional_price']) : 0.00;
        $active = isset($_POST['active']) ? intval($_POST['active']) : 1;

        if ($product_id > 0 && $weekday >= 1 && $weekday <= 7 && $promotional_price > 0) {
            $success = $productModel->addOrUpdateWeeklyPromotion($product_id, $weekday, $promotional_price, $active);
            if ($success) {
                header("Location: ../index.php?page=profile&action=promotions&status=saved");
                exit;
            }
        }
        header("Location: ../index.php?page=profile&action=promotions&status=invalid");
        exit;
    }
} else { // GET requests
    if ($action === 'delete') {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $success = $productModel->deleteWeeklyPromotion($id);
            if ($success) {
                header("Location: ../index.php?page=profile&action=promotions&status=deleted");
                exit;
            }
        }
        header("Location: ../index.php?page=profile&action=promotions&status=fail");
        exit;
    } elseif ($action === 'toggle') {
        $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        $weekday = isset($_GET['weekday']) ? intval($_GET['weekday']) : 0;
        $promotional_price = isset($_GET['price']) ? floatval($_GET['price']) : 0.00;
        $active = isset($_GET['active']) ? intval($_GET['active']) : 0;

        if ($product_id > 0 && $weekday >= 1 && $weekday <= 7) {
            $success = $productModel->addOrUpdateWeeklyPromotion($product_id, $weekday, $promotional_price, $active);
            if ($success) {
                header("Location: ../index.php?page=profile&action=promotions&status=toggled");
                exit;
            }
        }
        header("Location: ../index.php?page=profile&action=promotions&status=fail");
        exit;
    }
}

header("Location: ../index.php?page=profile&action=promotions");
exit;
