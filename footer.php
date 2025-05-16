<footer class="my-2 container-fluid bg-success">

    <p class="my-2 text-white">© Comércio de Utilidades Baobá LTDA - ME <?php echo date("Y"); ?>. Todos os direitos reservados.</p>
    <p class="m-0"><button onclick="moveTo('policies')" type="button" class="btn btn-outline-light my-2">Políticas e Privacidade</button> | <button onclick="moveTo('terms')" type="button" class="btn btn-outline-light my-2">Termos e Condições</button></p>
    <hr class="bg-light">
    <p class="text-white">Desenvolvido por Weslley Henrique Vieira Ferraz<br>
        Tenha um site ou app incrivel como esse faça um orçamento sem compromisso <a href="https://engenheirosoftwareweslley.com.br" target="_blank">clicando aqui</a></p>

    <p class="my-2 text-center text-white mt-2 mb-2">Copyright &copy; Weslley Henrique Vieira Ferraz <?php echo date("Y"); ?></p>
</footer>
<script src="./libs/bootstrap/jquery.js"></script>
<script src="./libs/bootstrap/popper.js"></script>
<script src="./libs/bootstrap/bootstrap.js"></script>
<script src="./libs/DataTables/datatables.js"></script>
<script src="./libs/tinymce/tinymce.min.js"></script>
<script src="./libs/select2/js/select2.js"></script>
<script src="./libs/alertifyjs/alertify.js"></script>

<script src="./utils/maskCPF.js"></script>
<script src="./utils/req_res_chatboot.js"></script>

<script>
    document.getElementById('phone').addEventListener('input', function(e) {
        let input = e.target.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
        input = input.substring(0, 11); // Limita a 11 dígitos

        // Aplica a máscara de acordo com a quantidade de dígitos
        if (input.length > 10) {
            input = `(${input.slice(0, 2)}) ${input.slice(2, 7)}-${input.slice(7, 11)}`;
        } else if (input.length > 6) {
            input = `(${input.slice(0, 2)}) ${input.slice(2, 6)}-${input.slice(6, 10)}`;
        } else if (input.length > 2) {
            input = `(${input.slice(0, 2)}) ${input.slice(2)}`;
        }

        e.target.value = input; // Atualiza o valor do campo com a máscara
    });
</script>

<script>
    $(document).ready(function() {
        $('.select_basic2').select2();
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [
                [0, "asc"]
            ], // Ordena a primeira coluna em ordem crescente
            "pageLength": 10, // Define o número de registros por página
            "searching": true // Habilita a pesquisa
        });
    });
</script>

<script>
    // tinymce.init({
    //     selector: 'textarea#description',
    //     plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    //     language: 'pt_BR',
    // });
</script>

<script>
    function moveTo(page) {
        window.location.href = `./${page}.php`;
    }
</script>

<!-- JavaScript para Visualizar a Imagem Selecionada -->
<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result; // Define o src da imagem com a URL do arquivo
            }

            reader.readAsDataURL(file); // Lê o conteúdo da imagem como URL
        }
    }
</script>