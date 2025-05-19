<?php
$pdo = new PDO('mysql:host=localhost;dbname=wesll523_baoba_ervas', 'wesll523_baoba_ervas', 'B@oba2014');
$query = $pdo->query("SELECT product_id, description FROM products");

foreach ($query as $row) {
    $id = $row['product_id'];
    $desc = html_entity_decode(strip_tags($row['description']));
    $desc = trim(preg_replace('/\s+/', ' ', $desc)); // remove espaços em excesso

    $stmt = $pdo->prepare("UPDATE products SET description = :desc WHERE product_id = :id");
    $stmt->execute([':desc' => $desc, ':id' => $id]);
}
?>
