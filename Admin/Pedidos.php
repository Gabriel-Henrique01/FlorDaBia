<?php 
    session_start();
    include '../conexao.php';

    if(!isset($_SESSION["IDAdmin"]) && empty($_SESSION["IDAdmin"])){
        header("Location: ../index.php");
    } 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="website icon" type="png" href="../img/Logo.png">
    <link rel="stylesheet" href="../css/ocasioes.css">
    <link rel="stylesheet" href="../css/header.css">
    <title>Pedidos Concluido</title>
    <link rel="stylesheet" href="../css/admin/pedidos.css">
</head>
<body>
  
  
    <!--MENU-->

    <header>
        <nav class="nav-bar">
            <div class="header">
                <a href="index.php">
                    <div class="logo-header">
                        <img class="logo" src="../img/logo.png" alt="Logo">
                    </div>
                </a>
            </div>
        </nav>
    </header>

    <div class="layout">
        <nav class="sidebar">
           <h3>Navegação:</h3>
            <ul>
                <li><a href="Admin.php">Inicial</a></li>
                <li><a href="Inserir.php">Inserir produtos</a></li>
                <li><a href="Pedidos.php">Pedidos concluidos</a></li>
                <li><a href="Produtos.php">Produtos</a></li>
            </ul>
        <div class="embaixo">
            <ul>
                <li><a href="../index.php">Página inicial</a></li>
                <li><a href="logout.php">Log-out</a></li>
            </ul>
            </div>
        </nav>

        <div class="container">
            <table>
                <!-- Sua tabela aqui -->
            </table>
        </div>
    </div>

    <?php
        if(isset($_GET['mensagem']) && !empty($_GET['mensagem'])){
            ?>
                <div class="alert alert-warning" style="color: #000;">
                    <?php echo $_GET['mensagem']?>
                </div>
            <?php
        }
    ?>

    <div class="container">
    <table>
        <tr>
            <th>NºPedido</th>
            <th>Cliente (ID)</th>
            <th>Produtos</th>
            <th>Preço total</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Status</th>
        </tr>
        <style>
            table {
                border-collapse: collapse;
            }
            th, td {
                padding: 10px;
                border: 1px solid black;
                }

        </style>
        <?php 
            // Modifique a consulta para buscar apenas pedidos com status "Pendente"
            $queryPedido = 'SELECT * FROM pedido WHERE Status = "Concluido"';
            $resultPedido = mysqli_query($conn, $queryPedido);

            // Loop através dos pedidos
            while($rowPedido  = mysqli_fetch_assoc($resultPedido)){

                // Busca o nome do cliente
                $queryCliente = 'SELECT Nome FROM cliente WHERE IDCliente = ' . $rowPedido['IDCliente'];
                $resultCliente = mysqli_query($conn, $queryCliente);
                $rowCliente =  mysqli_fetch_assoc($resultCliente);

                echo  "<tr>";
                echo  "<td>" . $rowPedido['IDPedido'] . "</td>";
                echo  "<td>" . $rowCliente['Nome'] . " (" .  $rowPedido['IDCliente'] . ")</td>";

                // Busca os produtos do pedido usando o ID do pedido
                $queryProdutosPedidos = 'SELECT * FROM produtospedidos WHERE IDPedido = ' . $rowPedido['IDPedido'];
                $resultProdutosPedidos  =  mysqli_query($conn, $queryProdutosPedidos);

                echo '<td>';
                // Loop através dos produtos do pedido
                while($rowProcessamento = mysqli_fetch_assoc($resultProdutosPedidos)){
                    // Busca os detalhes do produto
                    $queryProdutos = 'SELECT * FROM produtos WHERE ID = ' . $rowProcessamento['IDProduto'];
                    $resultProdutos = mysqli_query($conn, $queryProdutos);
                    $rowProdutos = mysqli_fetch_assoc($resultProdutos);

                    echo $rowProdutos['Nome'] . ' (' . $rowProcessamento['Quantidade'] . ') <br>';
                }
                
                echo '</td>';
                echo '<td>' . $rowPedido['PrecoTotal'] .  '</td>';
                echo '<td>' . $rowPedido['Data'] . '</td>';
                echo '<td>' . $rowPedido['Horario'] . '</td>';
                echo '<td>' . $rowPedido['Status'] . '</td>';
                echo '</tr>';
            }
        ?>

    </table>
    </div>

    <script src="../js/menu.js"></script>

</body>
</html>