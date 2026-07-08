<?php
$query = "
    SELECT pr.*, u.name as user_name, p.name as product_name, p.slogan as product_slogan 
    FROM product_ratings pr 
    JOIN users u ON pr.user_id = u.user_id 
    JOIN products p ON pr.product_id = p.product_id 
    ORDER BY pr.createDate DESC
";
$result = $conn->query($query);
$ratings_list = [];
if ($result) {
    $ratings_list = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<div class="card shadow-sm border-0 p-4" style="border-radius: 12px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-success mb-0 fw-bold"><i class="fa-solid fa-comments"></i> Avaliações dos Produtos</h4>
        <span class="badge bg-success p-2 fw-semibold" style="border-radius: 8px;"><?= count($ratings_list) ?> total</span>
    </div>

    <?php if (empty($ratings_list)): ?>
        <p class="text-muted text-center py-5 mb-0">Nenhuma avaliação cadastrada nos produtos ainda.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produto</th>
                        <th>Cliente</th>
                        <th>Nota</th>
                        <th>Comentário</th>
                        <th>Status Resposta</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ratings_list as $r): ?>
                        <tr>
                            <td class="fw-semibold text-dark" style="max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($r['product_name']) ?>
                            </td>
                            <td class="text-secondary small"><?= htmlspecialchars($r['user_name']) ?></td>
                            <td>
                                <span class="text-warning">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <i class="<?= $i <= $r['rating'] ? 'fa-solid' : 'fa-regular' ?> fa-star" style="font-size: 0.75rem;"></i>
                                    <?php endfor; ?>
                                </span>
                            </td>
                            <td class="text-muted small" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($r['comment'] ?? '') ?>">
                                <?= empty($r['comment']) ? '<em class="text-black-50">Sem comentário</em>' : htmlspecialchars($r['comment']) ?>
                            </td>
                            <td>
                                <?php if (!empty($r['admin_reply'])): ?>
                                    <span class="badge p-2" style="background-color: #d1e7dd; color: #0f5132; border-radius: 6px; font-size: 0.75rem;"><i class="fa-solid fa-circle-check"></i> Respondido</span>
                                <?php else: ?>
                                    <span class="badge p-2" style="background-color: #fff3cd; color: #664d03; border-radius: 6px; font-size: 0.75rem;"><i class="fa-solid fa-clock"></i> Pendente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="index.php?page=product&slogan=<?= urlencode($r['product_slogan']) ?>#reply-form-<?= $r['rating_id'] ?>" target="_blank" class="btn btn-sm btn-outline-success fw-bold" style="border-radius: 6px; font-size: 0.8rem;">
                                    <i class="fa-solid fa-reply"></i> Responder/Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
