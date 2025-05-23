<!-- views/ticket/panel.php -->
<div class="container mt-5">
    <h2><i class="fas fa-tv"></i> Painel de Atendimento</h2>
    <div id="painel" class="mt-4">
        <div class="alert alert-info">Carregando atendimentos...</div>
    </div>
</div>

<script>
    async function atualizarPainel() {
        try {
            const response = await fetch('api/painel.php');
            const chamados = await response.json();

            const painel = document.getElementById('painel');
            painel.innerHTML = '';

            if (chamados.length === 0) {
                painel.innerHTML = '<div class="alert alert-warning">Nenhum atendimento registrado hoje.</div>';
                return;
            }

            chamados.forEach(c => {
                const div = document.createElement('div');
                div.className = 'alert alert-secondary';
                div.innerHTML = `<strong>${c.ticket}</strong> chamado por Atendente <strong>${c.atendente}</strong> às <strong>${c.hora}</strong>`;
                painel.appendChild(div);
            });
        } catch (e) {
            console.error('Erro ao buscar dados do painel:', e);
        }
    }

    atualizarPainel(); // Chamada inicial
    setInterval(atualizarPainel, 3000); // Atualiza a cada 3 segundos
</script>
