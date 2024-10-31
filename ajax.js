$(document).ready(function() {
    $('#pesquisa-input').on('input', function() {
        const termoBusca = $(this).val();

        if (termoBusca.length > 0) { // Envia a solicitação somente se houver texto
            $.post("busca_sugestoes.php", { termo: termoBusca }, function(data) {
                $('#suggestions-dropdown').html(data).show(); // Exibe as sugestões
            });
        } else {
            $('#suggestions-dropdown').hide(); // Oculta o dropdown se o campo estiver vazio
        }
    });

    // Seleciona uma sugestão ao clicar
    $(document).on('click', '.suggestion-item', function() {
        const termoSelecionado = $(this).text();
        $('#pesquisa-input').val(termoSelecionado);
        $('#suggestions-dropdown').hide(); // Oculta o dropdown ao selecionar
    });

    // Oculta o dropdown ao clicar fora
    $(document).click(function(e) {
        if (!$(e.target).closest('.pesquisa').length) {
            $('#suggestions-dropdown').hide();
        }
    });
});
