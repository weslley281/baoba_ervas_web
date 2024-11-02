<?php
if (isset($_GET["slogan"])) {
    $get_product = $product->getBySlogan($_GET["slogan"]);
}elseif(isset($_GET["product_id"])){
    $get_product = $product->getById($_GET["product_id"]);
}else {
    
}
?>
<!-- Section-->
<section class="py-5">
    <h2 class="text-center mb-4">Nossos Produtos</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">

    </div>
</section>