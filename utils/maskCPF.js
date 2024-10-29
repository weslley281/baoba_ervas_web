// Função para aplicar a máscara de CPF
function maskCPF(input) {
  input.value = input.value
    .replace(/\D/g, '') // Remove caracteres não numéricos
    .replace(/^(\d{3})(\d)/, '$1.$2') // Adiciona ponto após os primeiros 3 dígitos
    .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3') // Adiciona ponto após os próximos 3 dígitos
    .replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})/, '$1.$2.$3-$4'); // Adiciona hífen e os últimos dígitos
}

// Seleciona o campo de CPF
var cpfInput = document.getElementById('cpf');

// Adiciona um listener para chamar a função de máscara ao digitar
cpfInput.addEventListener('input', function () {
  maskCPF(this);
});
