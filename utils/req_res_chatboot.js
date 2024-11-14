document.addEventListener('DOMContentLoaded', function () {
  function addMessage(message, isUser) {
    var messageClass = isUser ? 'text-end' : 'text-start';
    var messageElement = document.createElement('div');
    messageElement.className = 'mb-2 ' + messageClass;
    messageElement.textContent = message;
    document.getElementById('chat-messages').appendChild(messageElement);
  }

  function processQuestion() {
    var userQuestion = document.getElementById('user-message').value;

    addMessage(userQuestion, true);

    // Utilizando fetch no lugar do jQuery.ajax
    fetch('http://localhost/baoba_ervas_web/controllers/chatbot.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ pergunta: userQuestion }),
    })
      .then((response) => response.json())
      .then((data) => {
        addMessage(data.resposta, false);
      })
      .catch((error) => {
        console.error('Erro ao processar a pergunta:', error);
        addMessage('Desculpe, ocorreu um erro ao processar a pergunta.', false);
      });

    document.getElementById('user-message').value = '';
  }

  // Adicionando evento de clique no botão
  document.getElementById('send-button').addEventListener('click', function () {
    processQuestion();
  });

  // Adicionando evento de pressionamento da tecla "Enter"
  document
    .getElementById('user-message')
    .addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        processQuestion();
      }
    });
});
