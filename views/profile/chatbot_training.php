<?php
if (isset($_SESSION["user_id"]) && $_SESSION["user_type"] == "admin") {
    // Carrega o FAQ
    $faqFile = __DIR__ . '/../../controllers/faq.json';
    $faqData = [];
    if (file_exists($faqFile)) {
        $faqData = json_decode(file_get_contents($faqFile), true) ?? [];
    }
?>
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Treinamento do Chatbot (FAQ)</h2>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalTreinamento" onclick="prepararCadastro()">
                <i class="fa-solid fa-plus"></i> Adicionar Pergunta
            </button>
        </div>

        <div class="table-responsive">
            <table id="faqTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Palavra-Chave (Pergunta)</th>
                        <th>Resposta do Bot</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqData as $keyword => $response) { ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($keyword) ?></strong></td>
                            <td><?= htmlspecialchars($response) ?></td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-warning btn-sm me-2 mr-2" data-toggle="modal" data-target="#ModalTreinamento" onclick="prepararEdicao('<?= htmlspecialchars(addslashes($keyword)) ?>', '<?= htmlspecialchars(addslashes($response)) ?>')">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#ModalDeleteFaq" onclick="prepararExclusao('<?= htmlspecialchars(addslashes($keyword)) ?>')">
                                        <i class="fa-solid fa-trash"></i> Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Cadastro / Edição -->
    <div class="modal fade" id="ModalTreinamento" tabindex="-1" role="dialog" aria-labelledby="modalTreinamentoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTreinamentoLabel">Adicionar Nova Regra</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="./controllers/ChatbotTrainingController.php?action=save" method="POST">
                    <div class="modal-body">
                        <!-- Campo para a palavra-chave original quando estiver editando -->
                        <input type="hidden" id="old_keyword" name="old_keyword" value="">
                        
                        <div class="mb-3">
                            <label for="keyword" class="form-label">Palavra-Chave / Pergunta Curta</label>
                            <input type="text" class="form-control" id="keyword" name="keyword" required placeholder="Ex: frete, pagamento, horario">
                            <small class="text-muted">O robô responderá quando encontrar esta palavra ou expressão na pergunta do cliente.</small>
                        </div>
                        <div class="mb-3">
                            <label for="response" class="form-label">Resposta do Robô</label>
                            <textarea class="form-control" id="response" name="response" rows="5" required placeholder="Digite a resposta que o robô deve enviar ao cliente..."></textarea>
                            <small class="text-muted">Você pode pular linhas e estruturar a resposta. Use quebras de linha normais.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar Regra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Exclusão -->
    <div class="modal fade" id="ModalDeleteFaq" tabindex="-1" role="dialog" aria-labelledby="modalDeleteFaqLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="modalDeleteFaqLabel">Excluir Regra de FAQ</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="./controllers/ChatbotTrainingController.php?action=delete" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="delete_keyword" name="keyword" value="">
                        <p>Tem certeza de que deseja excluir a regra de resposta para a palavra-chave: <strong id="delete_keyword_display"></strong>?</p>
                        <p class="text-danger"><small>Essa ação é irreversível.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir Regra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function prepararCadastro() {
            document.getElementById('modalTreinamentoLabel').textContent = "Adicionar Nova Regra de Resposta";
            document.getElementById('old_keyword').value = "";
            document.getElementById('keyword').value = "";
            document.getElementById('response').value = "";
        }

        function prepararEdicao(keyword, response) {
            document.getElementById('modalTreinamentoLabel').textContent = "Editar Regra de Resposta";
            document.getElementById('old_keyword').value = keyword;
            document.getElementById('keyword').value = keyword;
            document.getElementById('response').value = response;
        }

        function prepararExclusao(keyword) {
            document.getElementById('delete_keyword').value = keyword;
            document.getElementById('delete_keyword_display').textContent = keyword;
        }

        $(document).ready(function() {
            // Destrói tabela antiga se houver para evitar duplicidade de DataTable
            if ($.fn.DataTable.isDataTable('#faqTable')) {
                $('#faqTable').DataTable().destroy();
            }
            $('#faqTable').DataTable({
                "order": [[0, "asc"]],
                "pageLength": 10,
                "searching": true
            });
        });
    </script>
<?php
} else {
    echo "<center><strong><h1>Você não tem permissão para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = './index.php?page=login'; }, 3000);";
    echo "</script>";
}
