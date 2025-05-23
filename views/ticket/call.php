<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-bullhorn"></i> Chamar Próximo</h2>
    <form action="./controllers/TicketController.php?action=call" method="POST">
        <p class="text-center mb-4">Informe o número do atendente que irá chamar o cliente.</p>

        <div class="form-group">
            <label for="atendente">Número do Atendente</label>
            <input type="number" name="atendente" id="atendente" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-success mt-2">
            <i class="fas fa-volume-up"></i> Chamar Cliente
        </button>
    </form>

    <?php if (isset($info)) : ?>
        <div class="alert alert-info mt-4">
            Chamando: <strong>Ticket <?= $info['ticket'] ?></strong> no Atendente <strong><?= $info['atendente'] ?></strong>
        </div>
    <?php endif; ?>
</div>
