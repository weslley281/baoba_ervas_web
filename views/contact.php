<div class="container mt-5 mb-5">
    <h2 class="mb-4">Fale Conosco</h2>
    <div class="row">
        <div class="col-md-6">
            <form action="/enviar-contato" method="post">
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
        <div class="col-md-6">
            <h4>Nossas Lojas</h4>
            <div class="mb-3">
                <strong>Loja Centro</strong><br>
                Rua das Ervas, 123<br>
                Centro, Cidade - UF<br>
                Tel: (11) 1234-5678
            </div>
            <div class="mb-3">
                <strong>Loja Norte</strong><br>
                Av. Saúde, 456<br>
                Bairro Norte, Cidade - UF<br>
                Tel: (11) 2345-6789
            </div>
            <div class="mb-3">
                <strong>Loja Sul</strong><br>
                Praça Bem-Estar, 789<br>
                Bairro Sul, Cidade - UF<br>
                Tel: (11) 3456-7890
            </div>
        </div>
    </div>
</div>