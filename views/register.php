<form action="./controllers/UserController.php?action=create" method="POST">
    <div class="form-group">
        <label for="name">Nome:</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>

    <div class="form-group">
        <label for="phone">Telefone:</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>

    <div class="form-group">
        <label for="cpf">CPF:</label>
        <input type="text" class="form-control" id="cpf" name="cpf">
    </div>

    <div class="form-group">
        <label for="email">Endereço de email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>

    <div class="form-group">
        <label for="postal_code">Código Postal</label>
        <input type="text" class="form-control" id="postal_code" name="postal_code">
    </div>

    <div class="form-group">
        <label for="address">Endereço</label>
        <input type="text" class="form-control" id="address" name="address">
    </div>

    <div class="form-group">
        <label for="complement">Complemento</label>
        <input type="text" class="form-control" id="complement" name="complement">
    </div>

    <div class="form-group">
        <label for="neighborhood">Bairro</label>
        <input type="text" class="form-control" id="neighborhood" name="neighborhood">
    </div>

    <div class="form-group">
        <label for="city">Cidade</label>
        <input type="text" class="form-control" id="city" name="city">
    </div>

    <div class="form-group">
        <label for="state">Estado</label>
        <select name="state" id="state">
            <option value="MT">Mato Grosso</option>
        </select>
    </div>

    <div class="form-group">
        <label for="country">País</label>
        <input type="text" class="form-control" id="country" name="country" value="Brasil" readonly>
    </div>

    <div class="form-group">
        <label for="gender">Gênero</label>
        <select class="form-control" name="gender" id="gender">
            <?php
            $gender = [
                'masculine' => 'masculino',
                'feminine' => 'feminino',
                'non-binary' => 'não binário',
                'gender-fluid' => 'genero fluído',
                'transgender' => 'transgenero',
                'agender' => 'agênero',
                'two-spirit' => 'dois espirito',
                'other' => 'outro',
                'null' => 'prefiro não dizer'
            ];

            foreach ($gender as $key => $value) {
                echo "<option value=\"$key\">$value</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="birth_date">Data de Nascimento</label>
        <input type="date" class="form-control" id="birth_date" name="birth_date">
    </div>

    <div class="form-group">
        <label for="birth_date">Senha</label>
        <input type="password" class="form-control" id="birth_date" name="birth_date">
    </div>

    <div class="form-group">
        <label for="birth_date">Repita a Senha</label>
        <input type="confirmPassword" class="form-control" id="birth_date" name="birth_date">
    </div>

    <div class="form-group mt-2">
        <button type="submit" class="btn btn-info">Salvar</button>
    </div>
</form>

<script>
    function validatePasswords() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (password !== confirmPassword) {
            alert("As senhas não coincidem!");
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio do formulário
    }
</script>