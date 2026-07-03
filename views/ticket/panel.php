<!-- views/ticket/panel.php -->
<div class="container mt-5">
    <div class="card shadow-lg border-0 overflow-hidden">
        <div class="card-header bg-success text-white text-center py-3">
            <h2 class="mb-0"><i class="fas fa-tv"></i> Painel de Atendimento</h2>
        </div>
        <div class="card-body bg-light p-4">
            <div class="row">
                <!-- Painel de Destaque -->
                <div class="col-md-7 mb-4 mb-md-0">
                    <div class="bg-white rounded-3 border p-4 text-center d-flex flex-column justify-content-center align-items-center shadow-sm h-100" style="min-height: 350px;">
                        <h4 class="text-muted text-uppercase mb-3">Senha Atual</h4>
                        <div id="senha-principal" class="display-1 fw-bold text-success my-3" style="font-size: 6rem; letter-spacing: 2px;">---</div>
                        <h5 class="text-secondary mt-3">ATENDENTE</h5>
                        <div id="atendente-principal" class="display-4 fw-semibold text-dark">---</div>
                    </div>
                </div>
                
                <!-- Histórico de Senhas -->
                <div class="col-md-5">
                    <div class="bg-white rounded-3 border p-4 shadow-sm h-100">
                        <h4 class="text-muted text-uppercase mb-4 text-center">Últimas Chamadas</h4>
                        <div id="historico-chamadas" class="d-flex flex-column gap-3">
                            <div class="text-center text-muted py-4">Carregando chamadas...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.3; }
        100% { opacity: 1; }
    }
    .blink-text {
        animation: blink 0.8s infinite;
    }
</style>

<script>
    let ultimoTicketChamado = null;

    function playBeep() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // Nota Lá (A5)
            gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime);
            
            oscillator.start();
            
            // Suaviza a saída do som
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.8);
            oscillator.stop(audioCtx.currentTime + 0.8);
        } catch (e) {
            console.error('Erro ao tocar bipe:', e);
        }
    }

    function falarSenha(ticket, atendente) {
        try {
            // Soletrar o ticket para ficar claro (ex: P 0 0 1)
            const senhaSoletrada = ticket.split('').join(' ');
            const texto = `Senha, ${senhaSoletrada}, no atendente, ${atendente}`;
            
            const utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'pt-BR';
            utterance.rate = 0.85; // Velocidade levemente reduzida para clareza
            window.speechSynthesis.speak(utterance);
        } catch (e) {
            console.error('Erro no Text-to-Speech:', e);
        }
    }

    async function atualizarPainel() {
        try {
            const response = await fetch('api/painel.php');
            const chamados = await response.json();

            const senhaPrincipal = document.getElementById('senha-principal');
            const atendentePrincipal = document.getElementById('atendente-principal');
            const historico = document.getElementById('historico-chamadas');

            if (chamados.length === 0) {
                senhaPrincipal.textContent = "---";
                atendentePrincipal.textContent = "---";
                historico.innerHTML = '<div class="text-center text-muted py-4">Nenhum atendimento registrado hoje.</div>';
                return;
            }

            // O mais recente é o último no array retornado (pois api/painel.php faz array_reverse de getCalledTickets)
            const maisRecente = chamados[chamados.length - 1];

            // Fala e toca som apenas se houver uma nova chamada de ticket
            if (maisRecente && maisRecente.ticket !== ultimoTicketChamado) {
                // Primeira execução: apenas registra sem falar para não assustar ao abrir a página
                if (ultimoTicketChamado !== null) {
                    playBeep();
                    setTimeout(() => {
                        falarSenha(maisRecente.ticket, maisRecente.atendente);
                    }, 800);
                }
                ultimoTicketChamado = maisRecente.ticket;
            }

            // Atualiza Destaque
            if (maisRecente) {
                senhaPrincipal.textContent = maisRecente.ticket;
                atendentePrincipal.textContent = "GUICHÊ " + maisRecente.atendente;
                
                // Pisca a senha para chamar a atenção temporariamente
                senhaPrincipal.classList.add('blink-text');
                setTimeout(() => {
                    senhaPrincipal.classList.remove('blink-text');
                }, 4000);
            }

            // Atualiza histórico (todos menos o mais recente)
            historico.innerHTML = '';
            const anteriores = chamados.slice(0, -1).reverse(); 

            if (anteriores.length === 0) {
                historico.innerHTML = '<p class="text-muted text-center py-4">Sem chamadas anteriores.</p>';
            } else {
                anteriores.forEach(c => {
                    const item = document.createElement('div');
                    item.className = 'd-flex justify-content-between align-items-center p-3 rounded bg-light border-start border-success border-4 mb-2';
                    item.innerHTML = `
                        <div>
                            <span class="fs-4 fw-bold text-success">${c.ticket}</span>
                            <span class="text-muted ms-2" style="font-size: 0.8rem;">(${c.hora})</span>
                        </div>
                        <div class="fw-semibold text-secondary">Guichê ${c.atendente}</div>
                    `;
                    historico.appendChild(item);
                });
            }
        } catch (e) {
            console.error('Erro ao buscar dados do painel:', e);
        }
    }

    atualizarPainel(); // Chamada inicial
    setInterval(atualizarPainel, 3000); // Atualiza a cada 3 segundos
</script>
