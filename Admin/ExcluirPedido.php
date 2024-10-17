<?php 
    session_start();
    include '../conexao.php';

    if (isset($_GET['IDPedido']) && !empty($_GET['IDPedido'])) {
        $IDPedido = $_GET['IDPedido'];

        // Exclui o pedido sem prepared statements
        $excluir = 'DELETE FROM pedido WHERE IDPedido = ' . intval($IDPedido); 
        $result = $conn->query($excluir);

        if ($result) {
            header('Location: Admin.php?mensagem=Pedido excluído com sucesso!');
        } else {
            header('Location: Admin.php?mensagem=Erro ao excluir o pedido!');
        }
    } else {
        header('Location: Admin.php?mensagem=Selecione um pedido para excluir');
    }
    exit();
?>
