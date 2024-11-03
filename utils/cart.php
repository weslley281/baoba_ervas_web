<?php
session_start();

function initializeCart()
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function adicionarAoCart($id, $name, $amount, $price)
{
    initializeCart();

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['amount'] += $amount;
    } else {
        $_SESSION['cart'][$id] = [
            'product_id' => $id,
            'name' => $name,
            'amount' => $amount,
            'price' => $price
        ];
    }
}

function removerDoCart($id)
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
            removerDoCart($id);
        }
    }
}

function exibirCart()
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

function calcularTotalCart()
{
    initializeCart();

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['amount'];
    }
    return $total;
}
?>
