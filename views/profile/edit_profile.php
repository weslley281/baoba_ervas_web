<form action="./controllers/UserController.php?action=update" method="POST">
    <input type="hidden" id="user_id" value="<?= $get_user["user_id"] ?>" name="user_id">

    <div class="form-group">
        <label for="name">Nome:</label>
        <input type="text" class="form-control" id="name" value="<?= $get_user["name"] ?>" name="name">
    </div>

    <div class="form-group">
        <label for="phone">Telefone:</label>
        <input type="text" class="form-control" id="phone" value="<?= $get_user["phone"] ?>" name="phone">
    </div>

    <div class="form-group">
        <label for="email">Endereço de email</label>
        <input type="email" class="form-control" id="email" value="<?= $get_user["email"] ?>" name="email">
    </div>

    <div class="form-group">
        <label for="postal_code">Código Postal</label>
        <div class="input-group">
            <input type="text" class="form-control" id="postal_code" maxlength="9"
                   value="<?= $get_user["postal_code"] ?>" name="postal_code"
                   oninput="mascaraCep(this)" placeholder="00000-000">
            <button type="button" class="btn btn-secondary" onclick="buscarCep()">Buscar</button>
        </div>
    </div>

    <div class="form-group">
        <label for="address">Endereço</label>
        <input type="text" class="form-control" id="address" value="<?= $get_user["address"] ?>" name="address">
    </div>

    <div class="form-group">
        <label for="complement">Complemento</label>
        <input type="text" class="form-control" id="complement" value="<?= $get_user["complement"] ?>" name="complement">
    </div>

    <div class="form-group">
        <label for="neighborhood">Bairro</label>
        <input type="text" class="form-control" id="neighborhood" value="<?= $get_user["neighborhood"] ?>" name="neighborhood">
    </div>

    <div class="form-group">
        <label for="city">Cidade</label>
        <input type="text" class="form-control" id="city" value="<?= $get_user["city"] ?>" name="city">
    </div>

    <div class="form-group">
        <label for="state">Estado</label>
        <input type="text" class="form-control" id="state" value="<?= $get_user["state"] ?>" name="state" readonly>
    </div>

    <div class="form-group">
        <label for="country">País</label>
        <input type="text" class="form-control" id="country" value="<?= $get_user["country"] ?>" name="country" readonly>
    </div>

    <div class="form-group">
        <label for="birth_date">Data de Nascimento</label>
        <input type="date" class="form-control" id="birth_date" value="<?= $get_user["birth_date"] ?>" name="birth_date" required>
    </div>

    <button type="submit" class="btn btn-info">Editar</button>
</form>

<script>
document.getElementById('postal_code').addEventListener('blur', function () {
    const cep = this.value.replace(/\D/g, '');

    if (cep.length !== 8) {
        alert("CEP inválido. Digite os 8 números corretamente.");
        return;
    }

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                alert("CEP não encontrado.");
                return;
            }

            document.getElementById('address').value = data.logradouro || '';
            document.getElementById('neighborhood').value = data.bairro || '';
            document.getElementById('city').value = data.localidade || '';
            document.getElementById('state').value = data.uf || '';
            document.getElementById('country').value = 'Brasil';
        })
        .catch(() => alert("Erro ao buscar o CEP. Tente novamente mais tarde."));
});
</script>
