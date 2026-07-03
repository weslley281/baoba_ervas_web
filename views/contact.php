<div class="container mt-5 mb-5">
    <h2 class="mb-4">Fale Conosco</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Nossas Lojas</h4>
            <?php foreach (STORES as $key => $store) { ?>
                <div class="mb-3">
                    <strong><?= htmlspecialchars($store['name']) ?></strong><br>
                    <?= htmlspecialchars($store['address']) ?><br>
                    WhatsApp: <a href="https://wa.me/<?= $store['phone'] ?>" target="_blank" class="text-success"><?= htmlspecialchars($store['phone']) ?></a>
                </div>
            <?php } ?>
        </div>

        <div class="col-md-6">
            <form action="./utils/send-contact.php" method="post">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="mensagem" class="form-label">Mensagem</label>
                    <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Enviar</button>
            </form>
        </div>
    </div>

    <hr class="my-4">
    
    <div class="row">
        <div class="col-md-12">
            <h4>Atendimento ao Cliente</h4>
            <p>Se você tiver alguma dúvida ou precisar de assistência, entre em contato conosco através do nosso WhatsApp:</p>
            <?php
            if (isset($_SESSION['preferred_store']) && isset(STORES[$_SESSION['preferred_store']])) {
                $store = STORES[$_SESSION['preferred_store']];
            ?>
                <a href="https://wa.me/<?= $store['phone'] ?>" target="_blank" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i> Conversar com <?= htmlspecialchars($store['short_name']) ?></a>
            <?php } else { ?>
                <a href="#" data-toggle="modal" data-target="#ModalWhatsapp" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i> Falar Conosco</a>
            <?php } ?>
        </div>
        <div class="col-md-12 mt-4">
            <h4>Horário de Funcionamento</h4>
            <ul class="list-unstyled">
                <li><strong>Segunda a Sexta:</strong> 08:00 às 18:00</li>
                <li><strong>Sábado:</strong> 08:00 às 14:00</li>
                <li><strong>Domingo e Feriados:</strong> Fechado</li>
            </ul>
        </div>
    </div>
</div>