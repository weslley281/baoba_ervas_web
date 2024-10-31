<!-- Lista de Produtos -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Lista de Produtos</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Desconto</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $get_products = $product->getAll(); // Supondo que você tenha um método para buscar todos os produtos

            foreach ($get_products as $product) : ?>
                <tr>
                    <td><img src="<?php echo $product['path_image']; ?>" width="50" height="50" alt="Imagem"></td>
                    <td><?php echo $product['name']; ?></td>
                    <td>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                    <td><?php echo $product['discount']; ?>%</td>
                    <td><?php echo $product['stock_quantity']; ?></td>
                    <td>
                        <a href="index.php?page=profile&action=product&action2=edit&id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="productController.php?action=delete&id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>