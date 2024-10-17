<?php 
    session_start();
    include '../conexao.php';

    if (isset($_GET['IDPedido']) && !empty($_GET['IDPedido'])) {
        $IDPedido = $_GET['IDPedido'];

        // Exclui o pedido sem prepared statements
        $excluir = 'UPDATE pedido SET Status = "Concluido" WHERE IDPedido = ' . intval($IDPedido); 
        $result = $conn->query($excluir);

        if ($result) {
            header('Location: Admin.php?mensagem=Pedido concluido com sucesso!');
        } else {
            header('Location: Admin.php?mensagem=Erro ao concluir o pedido!');
        }
    } else {
        header('Location: Admin.php?mensagem=Selecione um pedido para concluir');
    }
    exit();
?>
