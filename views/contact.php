<div class="container mt-5 mb-5">
    <h2 class="mb-4">Fale Conosco</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Nossas Lojas</h4>
            <div class="mb-3">
                <strong>Loja Centro - Cuiabá</strong><br>
                Rua Joaquim Murtinho, 319<br>
                Centro Norte, Cuabá - MT<br>
                Tel: (65) 3025-7187
            </div>
            <div class="mb-3">
                <strong>Loja Porto - Cuiabá</strong><br>
                Rua Prof Feliciano Galdino, 585<br>
                Porto, Cuiabá - MT<br>
                Tel: (65) 3023-9010
            </div>
            <div class="mb-3">
                <strong>Loja Centro - Várzea Grande</strong><br>
                Praça Bem-Estar, 789<br>
                Centro, Várzea Grande - MT<br>
                Tel: (65) 3362-1007
            </div>
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
            <a href="https://api.whatsapp.com/send?phone=556530239010" target="_blank" class="btn btn-success">WhatsApp</a>
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