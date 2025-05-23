<!-- views/ticket/gerar.php -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 w-100" style="max-width: 500px;">
        <h3 class="text-center mb-4"><i class="fas fa-ticket-alt"></i> Gerar Ticket</h3>

        <p class="text-center mb-4">Escolha o tipo de ticket que deseja gerar:</p>
        <div class="text-center mb-4">
            <img src="assets/img/ticket.png" alt="Ticket" class="img-fluid" style="max-width: 150px;">
        </div>
        <p class="text-center mb-4">Clique no botão abaixo para gerar um ticket comum ou prioritário.</p>
        <p class="text-center mb-4">Os tickets prioritários têm prioridade no atendimento.</p>
        <p class="text-center mb-4">Após gerar o ticket, você receberá um número de atendimento.</p>
        <p class="text-center mb-4">Aguarde sua vez e dirija-se ao atendente quando chamado.</p>
        <p class="text-center mb-4">Agradecemos pela sua preferência!</p>

        <div class="d-flex flex-column gap-3">
            <form action="./controllers/TicketController.php?action=generate" method="POST">
                <input type="hidden" name="tipo" value="comum">
                <button type="submit" class="btn btn-primary btn-lg mb-2">
                    <i class="fas fa-user"></i> Gerar Ticket Comum
                </button>
            </form>
            
            <form action="./controllers/TicketController.php?action=generate" method="POST">
                <input type="hidden" name="tipo" value="prioritario">
                <button type="submit" class="btn btn-danger btn-lg">
                    <i class="fas fa-user-shield"></i> Gerar Ticket Prioritário
                </button>
            </form>
        </div>
    </div>
</div>