<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Atendimento TV - Várzea Grande</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
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

        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.3; }
            100% { opacity: 1; }
        }
        .blink-text {
            animation: blink 0.8s infinite;
        }
    </style>
</head>
<body class="p-4 p-md-5">

    <div id="panel-card" class="card shadow-lg border-0 overflow-hidden position-relative mx-auto" style="max-width: 1200px; border-radius: 16px;">
        <!-- Botão flutuante para fechar tela cheia (apenas visível em fullscreen) -->
        <button id="btn-exit-fullscreen" class="btn btn-dark btn-sm rounded-circle shadow" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; width: 50px; height: 50px; display: none;" onclick="toggleFullScreen()">
            <i class="fa-solid fa-xmark fa-xl"></i>
        </button>

        <!-- Header -->
        <div class="card-header bg-success text-white text-center py-3 position-relative">
            <h2 class="mb-0"><i class="fas fa-tv"></i> Painel de Chamadas - Várzea Grande</h2>
            <button id="btn-fullscreen" class="btn btn-outline-light btn-sm" style="position: absolute; top: 15px; right: 15px; border-radius: 8px;" onclick="toggleFullScreen()">
                <i class="fa-solid fa-expand"></i> Tela Cheia
            </button>
        </div>

        <!-- Body -->
        <div class="card-body bg-light p-4">
            <!-- Alerta de bloqueio de autoplay do navegador -->
            <div id="autoplay-banner" class="alert alert-warning text-center fw-bold shadow-sm mb-4" style="cursor: pointer; border-radius: 8px;" onclick="enableAudio()">
                <i class="fa-solid fa-volume-high"></i> O navegador bloqueou o som automático. Clique aqui (ou em qualquer lugar da tela) para ativar o áudio das chamadas!
            </div>
            
            <div class="row">
                <!-- Painel de Destaque (Esquerda) -->
                <div class="col-md-7 mb-4 mb-md-0">
                    <div class="bg-white rounded-3 border p-4 text-center d-flex flex-column justify-content-center align-items-center shadow-sm h-100" style="min-height: 380px; border-radius: 12px;">
                        <h4 class="text-muted text-uppercase mb-3 fw-bold">Senha Atual</h4>
                        <div id="senha-principal" class="display-1 fw-bold text-success my-3" style="font-size: 6.5rem; letter-spacing: 2px;">---</div>
                        <h5 class="text-secondary mt-3 fw-semibold">ATENDENTE</h5>
                        <div id="atendente-principal" class="display-4 fw-semibold text-dark">---</div>
                    </div>
                </div>
                
                <!-- Histórico de Senhas (Direita) -->
                <div class="col-md-5">
                    <div class="bg-white rounded-3 border p-4 shadow-sm h-100" style="border-radius: 12px;">
                        <h4 class="text-muted text-uppercase mb-4 text-center fw-bold">Últimas Chamadas</h4>
                        <div id="historico-chamadas" class="d-flex flex-column gap-3">
                            <div class="text-center text-muted py-4">Carregando chamadas...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let ultimoTicketChamado = null;
        let audioContextAtivo = false;

        // Ativa o contexto de áudio com interação humana
        function enableAudio() {
            try {
                const tempCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (tempCtx.state === 'suspended') {
                    tempCtx.resume();
                }
                audioContextAtivo = true;
                playBeep();
                const banner = document.getElementById('autoplay-banner');
                if (banner) {
                    banner.classList.add('d-none');
                }
            } catch (e) {
                console.error('Erro ao ativar contexto de áudio:', e);
            }
        }

        document.addEventListener('click', function() {
            if (!audioContextAtivo) {
                enableAudio();
            }
        });

        // Som sintetizado de sino duplo
        function playBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                
                gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(1, audioCtx.currentTime + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                
                oscillator.start(audioCtx.currentTime);
                
                setTimeout(() => {
                    const osc2 = audioCtx.createOscillator();
                    const gain2 = audioCtx.createGain();
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
                    gain2.gain.setValueAtTime(0, audioCtx.currentTime);
                    gain2.gain.linearRampToValueAtTime(1, audioCtx.currentTime + 0.05);
                    gain2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
                    osc2.start(audioCtx.currentTime);
                    osc2.stop(audioCtx.currentTime + 0.6);
                }, 220);

                oscillator.stop(audioCtx.currentTime + 0.5);
            } catch (e) {
                console.error('Erro ao reproduzir beep:', e);
            }
        }

        // Síntese de Voz (TTS)
        function falarSenha(senha, guiche) {
            if ('speechSynthesis' in window) {
                const prefix = senha.charAt(0);
                const digits = senha.substring(1);
                
                const soletrado = (prefix === 'P') ? 'Prioritária' : 'Comum';
                
                const texto = `Senha, ${soletrado} número ${parseInt(digits)}, no guichê ${guiche}`;
                const utterance = new SpeechSynthesisUtterance(texto);
                utterance.lang = 'pt-BR';
                utterance.rate = 0.95; 
                window.speechSynthesis.speak(utterance);
            }
        }

        // Atualização e Monitoramento AJAX
        async function atualizarPainel() {
            try {
                const response = await fetch('api/painel.php');
                if (!response.ok) return;
                const chamados = await response.json();

                const senhaPrincipal = document.getElementById('senha-principal');
                const atendentePrincipal = document.getElementById('atendente-principal');
                const historico = document.getElementById('historico-chamadas');

                if (chamados.length === 0) {
                    senhaPrincipal.textContent = "---";
                    atendentePrincipal.textContent = "---";
                    historico.innerHTML = '<p class="text-muted text-center py-4">Sem chamadas hoje.</p>';
                    return;
                }

                const maisRecente = chamados[chamados.length - 1];

                // Identifica novos chamados concorrentes para anúncios
                if (ultimoTicketChamado === null) {
                    ultimoTicketChamado = maisRecente.ticket;
                } else {
                    const indexUltimo = chamados.findIndex(c => c.ticket === ultimoTicketChamado);
                    let novosChamados = [];
                    
                    if (indexUltimo === -1) {
                        novosChamados = [maisRecente];
                    } else {
                        novosChamados = chamados.slice(indexUltimo + 1);
                    }

                    if (novosChamados.length > 0) {
                        novosChamados.forEach((c, idx) => {
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

                // Destaque Principal
                if (maisRecente) {
                    senhaPrincipal.textContent = maisRecente.ticket;
                    atendentePrincipal.textContent = "GUICHÊ " + maisRecente.atendente;
                    
                    // Se for novo, pisca a tela
                    const indexUltimo = chamados.findIndex(c => c.ticket === ultimoTicketChamado);
                    if (indexUltimo === chamados.length - 1) {
                        senhaPrincipal.classList.add('blink-text');
                        setTimeout(() => {
                            senhaPrincipal.classList.remove('blink-text');
                        }, 4000);
                    }
                }

                // Histórico de Chamadas Anteriores
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
                console.error('Erro de rede ou JSON:', e);
            }
        }

        // Função de Tela Cheia
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

        // Polling de 3 segundos
        atualizarPainel();
        setInterval(atualizarPainel, 3000);
    </script>
</body>
</html>
