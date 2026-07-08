<?php
session_start();
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?page=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : 'rate';
    $slogan = isset($_POST['slogan']) ? $_POST['slogan'] : '';
    $productModel = new Product($conn);

    if ($action === 'reply' && $_SESSION['user_type'] === 'admin') {
        $rating_id = isset($_POST['rating_id']) ? intval($_POST['rating_id']) : 0;
        $admin_reply = isset($_POST['admin_reply']) ? trim($_POST['admin_reply']) : '';

        if ($rating_id > 0) {
            $success = $productModel->addAdminReply($rating_id, $admin_reply);
            if ($success) {
                header("Location: ../index.php?page=product&slogan=" . urlencode($slogan) . "&success=reply_saved");
                exit;
            }
        }
    } else { // rate
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

        if ($product_id > 0 && $rating >= 1 && $rating <= 5) {
            $success = $productModel->addOrUpdateRating($product_id, $_SESSION['user_id'], $rating, $comment);
            if ($success) {
                header("Location: ../index.php?page=product&slogan=" . urlencode($slogan) . "&success=rating_saved");
                exit;
            }
        }
    }
}

header("Location: ../index.php");
exit;
