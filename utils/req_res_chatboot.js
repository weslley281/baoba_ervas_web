document.addEventListener('DOMContentLoaded', function () {
  function addMessage(message, isUser) {
    var bubble = document.createElement('div');
    bubble.className = 'chat-bubble ' + (isUser ? 'chat-bubble-user' : 'chat-bubble-bot');
    
    if (isUser) {
      bubble.textContent = message;
    } else {
      // Processamento básico de Markdown para o Bot
      var formatted = message.replace(/\n/g, '<br>');
      // Negrito **texto** -> <strong>texto</strong>
      formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
      // Links [texto](url) -> <a href="url"...>texto</a>
      formatted = formatted.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-success fw-bold" style="text-decoration: underline;">$1</a>');
      
      bubble.innerHTML = formatted;
    }

    var wrapper = document.createElement('div');
    wrapper.className = 'w-100 mb-2 ' + (isUser ? 'text-end' : 'text-start');
    wrapper.appendChild(bubble);

    var chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  }

  function addSuggestions(options) {
    var container = document.createElement('div');
    container.className = 'd-flex flex-wrap gap-1 mt-2 mb-3 justify-content-start';
    
    options.forEach(function(opt) {
      var btn = document.createElement('button');
      btn.className = 'btn btn-outline-success btn-sm rounded-pill my-1 mx-1 px-3';
      btn.style.fontSize = '0.8rem';
      btn.textContent = opt;
      btn.addEventListener('click', function() {
        var messageInput = document.getElementById('user-message');
        if (messageInput) {
          messageInput.value = opt;
          processQuestion();
        }
        container.remove(); // Remove as sugestões após clique
      });
      container.appendChild(btn);
    });

    var chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.appendChild(container);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  }

  function showTypingIndicator() {
    var indicator = document.createElement('div');
    indicator.id = 'chat-typing-indicator';
    indicator.className = 'chat-bubble chat-bubble-bot text-start mb-2 d-flex align-items-center';
    indicator.style.padding = '12px 20px';
    indicator.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';

    var wrapper = document.createElement('div');
    wrapper.className = 'w-100 mb-2 text-start';
    wrapper.appendChild(indicator);

    var chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  }

  function removeTypingIndicator() {
    var indicator = document.getElementById('chat-typing-indicator');
    if (indicator) {
        indicator.parentElement.remove();
    }
  }

  // Mensagem inicial de boas-vindas do assistente
  addMessage("Olá! Sou o assistente virtual da Baobá Ervas. Como posso ajudar você hoje?", false);
  addSuggestions([
    "Horário de Funcionamento", 
    "Formas de Pagamento", 
    "Contatos e Lojas", 
    "Chá de Camomila"
  ]);

  function processQuestion() {
    var messageInput = document.getElementById('user-message');
    var userQuestion = messageInput.value.trim();
    if (!userQuestion) return;

    addMessage(userQuestion, true);
    messageInput.value = '';

    showTypingIndicator();
    var startTime = Date.now();

    fetch('controllers/chatbot.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ pergunta: userQuestion }),
    })
      .then((response) => response.json())
      .then((data) => {
        var elapsed = Date.now() - startTime;
        var delay = Math.max(0, 600 - elapsed); // Garante pelo menos 600ms de animação "Digitando"
        
        setTimeout(function() {
            removeTypingIndicator();
            addMessage(data.resposta, false);
        }, delay);
      })
      .catch((error) => {
        console.error('Erro ao processar a pergunta:', error);
        setTimeout(function() {
            removeTypingIndicator();
            addMessage('Desculpe, ocorreu um erro ao processar a pergunta. Tente novamente mais tarde.', false);
        }, 600);
      });
  }

  // Adicionando evento de clique no botão
  const sendBtn = document.getElementById('send-button');
  if (sendBtn) {
    sendBtn.addEventListener('click', function () {
      processQuestion();
    });
  }

  // Adicionando evento de pressionamento da tecla "Enter"
  const userMsgInput = document.getElementById('user-message');
  if (userMsgInput) {
    userMsgInput.addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        processQuestion();
      }
    });
  }
});
