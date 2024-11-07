<?php
session_start();
include './utils/cart.php';  // Inclua o arquivo com suas funções

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if (strpos($action, 'update_') === 0) {
        $id = str_replace('update_', '', $action);
        $newAmount = $_POST['quantities'][$id];
        updateAmount($id, $newAmount);
    } elseif (strpos($action, 'remove_') === 0) {
        $id = str_replace('remove_', '', $action);
        removeCart($id);
    }
}

header("Location: index.php?page=cart");
exit;
