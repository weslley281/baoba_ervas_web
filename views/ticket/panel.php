<!-- views/ticket/panel.php -->
<div class="container mt-5">
    <div id="panel-card" class="card shadow-lg border-0 overflow-hidden position-relative">
        <!-- Botão flutuante para fechar tela cheia (apenas visível em fullscreen) -->
        <button id="btn-exit-fullscreen" class="btn btn-dark btn-sm rounded-circle shadow" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; width: 50px; height: 50px; display: none;" onclick="toggleFullScreen()">
            <i class="fa-solid fa-xmark fa-xl"></i>
        </button>
        <div class="card-header bg-success text-white text-center py-3 position-relative">
            <h2 class="mb-0"><i class="fas fa-tv"></i> Painel de Atendimento</h2>
            <button id="btn-fullscreen" class="btn btn-outline-light btn-sm" style="position: absolute; top: 15px; right: 15px; border-radius: 8px;" onclick="toggleFullScreen()">
                <i class="fa-solid fa-expand"></i> Tela Cheia
            </button>
        </div>
        <div class="card-body bg-light p-4">
            <!-- Alerta de bloqueio de autoplay do navegador -->
            <div id="autoplay-banner" class="alert alert-warning text-center fw-bold shadow-sm mb-4" style="cursor: pointer; border-radius: 8px;" onclick="enableAudio()">
                <i class="fa-solid fa-volume-high"></i> O navegador bloqueou o som automático. Clique aqui (ou em qualquer lugar da tela) para ativar o áudio das chamadas!
            </div>
            
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

    /* Estilos do Modo Tela Cheia Dedicado (Esconde o cabeçalho e destaca as senhas) */
    #panel-card:fullscreen {
        border-radius: 0 !important;
        background-color: #0b2e1b !important;
        border: none !important;
    }
    #panel-card:fullscreen .card-header {
        display: none !important;
    }
    #panel-card:fullscreen .card-body {
        background-color: #0b2e1b !important;
        height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        padding: 3rem !important;
    }
    #panel-card:fullscreen .row {
        width: 100% !important;
        height: 100% !important;
        align-items: stretch !important;
    }
    #panel-card:fullscreen .bg-white {
        background-color: #124d2e !important;
        color: #ffffff !important;
        border-color: #1d7243 !important;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3) !important;
    }
    #panel-card:fullscreen #senha-principal {
        color: #2ebd6e !important;
        font-size: 11rem !important;
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
    }
    #panel-card:fullscreen #atendente-principal {
        color: #ffffff !important;
        font-size: 5rem !important;
    }
    #panel-card:fullscreen .text-muted {
        color: #a3cfb6 !important;
        font-size: 1.5rem !important;
    }
    #panel-card:fullscreen .text-secondary {
        color: #a3cfb6 !important;
        font-size: 2rem !important;
    }
    #panel-card:fullscreen #btn-exit-fullscreen {
        display: flex !important;
        align-items: center;
        justify-content: center;
    }
    #panel-card:fullscreen #autoplay-banner {
        margin-bottom: 2rem !important;
    }
    #panel-card:fullscreen .bg-light {
        background-color: #124d2e !important;
        border-color: #1d7243 !important;
        color: #ffffff !important;
    }
    #panel-card:fullscreen .bg-light .text-success {
        color: #2ebd6e !important;
        font-size: 2rem !important;
    }
    #panel-card:fullscreen .bg-light .text-muted {
        color: #a3cfb6 !important;
    }
    #panel-card:fullscreen .bg-light .text-secondary {
        color: #ffffff !important;
        font-size: 1.5rem !important;
    }
</style>

<script>
    let ultimoTicketChamado = null;
    let audioContextAtivo = false;

    // Função para habilitar o áudio com interação
    function enableAudio() {
        try {
            const tempCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (tempCtx.state === 'suspended') {
                tempCtx.resume();
            }
            audioContextAtivo = true;
            
            // Toca som de confirmação
            playBeep();
            
            // Oculta o banner de aviso
            const banner = document.getElementById('autoplay-banner');
            if (banner) {
                banner.classList.add('d-none');
            }
        } catch (e) {
            console.error('Erro ao ativar contexto de áudio:', e);
        }
    }

    // Registra clique geral para ativação automática
    document.addEventListener('click', function() {
        if (!audioContextAtivo) {
            enableAudio();
        }
    });

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

            // Identifica chamados novos concorrentes para enfileirar as falas
            if (ultimoTicketChamado === null) {
                // Primeira carga da página: apenas registra o estado atual sem emitir som
                if (chamados.length > 0) {
                    ultimoTicketChamado = chamados[chamados.length - 1].ticket;
                }
            } else {
                const indexUltimo = chamados.findIndex(c => c.ticket === ultimoTicketChamado);
                let novosChamados = [];
                
                if (indexUltimo === -1) {
                    // Caso a senha anterior não esteja no histórico recente, trata a última como nova
                    if (maisRecente) {
                        novosChamados = [maisRecente];
                    }
                } else {
                    // Filtra apenas os novos chamados após o último que registramos
                    novosChamados = chamados.slice(indexUltimo + 1);
                }

                if (novosChamados.length > 0) {
                    novosChamados.forEach((c, idx) => {
                        // Enfileira os alertas sonoros com intervalo de 4.5 segundos para não sobrepor vozes
                        setTimeout(() => {
                            playBeep();
                            setTimeout(() => {
                                falarSenha(c.ticket, c.atendente);
                            }, 800);
                        }, idx * 4500);
                    });
                    ultimoTicketChamado = novosChamados[novosChamados.length - 1].ticket;
                }
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

    function toggleFullScreen() {
        const card = document.getElementById('panel-card');
        const btn = document.getElementById('btn-fullscreen');
        if (!document.fullscreenElement) {
            card.requestFullscreen().then(() => {
                btn.innerHTML = '<i class="fa-solid fa-compress"></i> Sair da Tela Cheia';
            }).catch(err => {
                console.error(`Erro ao ativar tela cheia: ${err.message}`);
            });
        } else {
            document.exitFullscreen().then(() => {
                btn.innerHTML = '<i class="fa-solid fa-expand"></i> Tela Cheia';
            });
        }
    }

    document.addEventListener('fullscreenchange', () => {
        const btn = document.getElementById('btn-fullscreen');
        if (btn) {
            if (document.fullscreenElement) {
                btn.innerHTML = '<i class="fa-solid fa-compress"></i> Sair da Tela Cheia';
            } else {
                btn.innerHTML = '<i class="fa-solid fa-expand"></i> Tela Cheia';
            }
        }
    });
</script>
