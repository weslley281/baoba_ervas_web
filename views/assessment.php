<!-- Section-->
<section class="py-5">
    <form class="mt-5" action="./utils/assessment.php" method="POST">
        <div class="form-group my-2">
            <label for="email">Endereço de email</label>
            <input type="email" class="form-control" id="email" placeholder="Seu email" name="email" required>
        </div>
        <div class="form-group my-2">
            <label for="phone">Telefone</label>
            <input type="tel" class="form-control" id="phone" placeholder="Seu Telefone" name="phone" required>
        </div>
        <div class="form-group my-2">
            <label for="assessment">Avaliação</label>
            <textarea class="form-control" name="assessment" id="assessment" cols="30" rows="10"></textarea>
        </div>

        <!-- Campo de Avaliação com 5 Estrelas -->
        <div class="form-group my-3">
            <label for="rating">Avaliação (1 a 5):</label>
            <div id="rating" class="d-flex flex-row-reverse">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5">
                    <i class="fas fa-star"></i>
                </label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">
                    <i class="fas fa-star"></i>
                </label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">
                    <i class="fas fa-star"></i>
                </label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">
                    <i class="fas fa-star"></i>
                </label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">
                    <i class="fas fa-star"></i>
                </label>
            </div>
        </div>

        <div class="form-group my-2">
            <button type="submit" class="btn btn-success">Enviar</button>
        </div>
    </form>
</section>

<style>
    input[type="radio"] {
        display: none;
    }

    #rating {
        flex-direction: row-reverse;
        /* Corrige a direção da seleção */
        gap: 5px;
    }

    label i.fas {
        font-size: 1.8rem;
        color: #ccc;
        transition: color 0.3s;
        cursor: pointer;
    }

    input[type="radio"]:checked~label i.fas {
        color: #FFD700;
    }

    label:hover i.fas,
    label:hover~label i.fas {
        color: #FFD700;
    }
</style>