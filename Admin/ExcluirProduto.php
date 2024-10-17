<?php 
    session_start();
    include '../conexao.php';

    if (isset($_GET['ID']) && !empty($_GET['ID'])) {
        $IDProdutos = $_GET['ID'];

        // Exclui o pedido sem prepared statements
        $excluir = 'DELETE FROM produtos WHERE ID = ' . intval($IDProdutos); 
        $result = $conn->query($excluir);

        if ($result) {
            header('Location: Produtos.php?mensagem=Produto excluído com sucesso!');
        } else {
            header('Location: Produtos.php?mensagem=Erro ao excluir o produdo!');
        }
    } else {
        header('Location: Produtos.php?mensagem=Selecione um produto para excluir');
    }
    exit();
?>
