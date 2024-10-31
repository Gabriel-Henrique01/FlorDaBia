function formatarNumero(input) {
    // Remove todos os caracteres não numéricos
    let valor = input.value.replace(/\D/g, '');

    // Adiciona os parênteses e o hífen na formatação desejada
    if (valor.length > 2) {
        valor = `(${valor.slice(0, 2)}) ${valor.slice(2, 7)}-${valor.slice(7, 11)}`;
    } else if (valor.length > 0) {
        valor = `(${valor.slice(0, 2)}`;
    }

    // Atualiza o valor do campo de entrada
    input.value = valor;
}

function salvarNumero(input) {
    // Remove os parênteses e o hífen antes de salvar
    const numeroSalvo = input.value.replace(/\D/g, '');
    console.log("Número salvo:", numeroSalvo);
    // Aqui você pode enviar o númeroSalvo para o servidor ou usá-lo conforme necessário
}
