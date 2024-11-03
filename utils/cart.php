<?php
function initializeCart()
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function addCart($id, $data)
{
    initializeCart();

    try {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['amount'] += $data["amount"];
        } else {
            $_SESSION['cart'][$id] = [
                'product_id' => $id,
                'name' => $data["name"],
                'amount' => $data["amount"],
                'price' => $data["price"],
                'path_image' => $data["path_image"]
            ];
        }
        return true;
    } catch (\Throwable $th) {
        return false;
    }
    
}

function removeCart($id)
{
    initializeCart();

    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

function atualizarAmount($id, $amount)
{
    initializeCart();

    if (isset($_SESSION['cart'][$id])) {
        if ($amount > 0) {
            $_SESSION['cart'][$id]['amount'] = $amount;
        } else {
            removeCart($id);
        }
    }
}

function showCart()
{
    initializeCart();

    echo "<h2>Cart de Compras</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>O cart está vazio.</p>";
        return;
    }

    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<p>Produto: {$item['name']} | Amount: {$item['amount']} | Preço: R$ " . number_format($item['price'], 2) . "</p>";
    }
}

function calculateTotalCart()
{
    initializeCart();

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['amount'];
    }
    return $total;
}
?>
