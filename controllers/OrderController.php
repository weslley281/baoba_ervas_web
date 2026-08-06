<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Sale.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../index.php?page=login");
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$saleModel = new Sale($conn);

if ($action === 'update_status') {
    $sale_id = isset($_POST['sale_id']) ? intval($_POST['sale_id']) : 0;
    $situation = isset($_POST['situation']) ? trim($_POST['situation']) : '';
    
    if ($sale_id > 0 && !empty($situation)) {
        $saleModel->updateSituation($sale_id, $situation);
        header("Location: ../index.php?page=profile&action=sales&status=updated");
        exit;
    }
} elseif ($action === 'delete') {
    $sale_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($sale_id > 0) {
        $saleModel->delete($sale_id);
        header("Location: ../index.php?page=profile&action=sales&status=deleted");
        exit;
    }
}

header("Location: ../index.php?page=profile&action=sales");
exit;
