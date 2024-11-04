function formatNumber(input) {
            // Remove tudo que não é dígito ou ponto
            input.value = input.value
                .replace(/[^\d.]/g, '') // Mantém apenas dígitos e pontos
                .replace(/(\..*)\./g, '$1'); // Remove pontos após o primeiro ponto
        }

        document.addEventListener('DOMContentLoaded', function () {
            var phoneInput = document.getElementById('phone');

            // Adiciona o evento de entrada ao campo "phone"
            phoneInput.addEventListener('input', function () {
                formatNumber(this);
            });
        });