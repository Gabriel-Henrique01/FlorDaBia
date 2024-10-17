document.getElementById('formCadastro').addEventListener('submit', function(event) {
    var senha = document.getElementById('senha').value;
    var senha1 = document.getElementById('senha1').value;

    if (senha !== senha1) {
        event.preventDefault(); // Impede o envio do formulário
        alert('As senhas não coincidem. Por favor, tente novamente.'); // Mensagem de erro
    }
});