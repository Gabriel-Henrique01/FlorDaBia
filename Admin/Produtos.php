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
    <link rel="website icon" type="png" href="../img/Logo.png">
    <link rel="stylesheet" href="../css/reset">
    <link rel="stylesheet" href="../css/ocasioes.css">
    <title>Produtos</title>
    <link rel="stylesheet" href="../css/Admin/Produtos.css">
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
                <div class="menu-icon" onclick="toggleMenu()">
                    <img id="menuToggle" src="../img/iconmenu.svg" alt="Menu">
                </div>
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
                </div>
            </div>
        </nav>
    </header>
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
                <th>ID</th>
                <th>Nome</th>
                <th>Valor</th>
                <th>Quantidade</th>
                <th>Tipo</th>
                <th>Ação</th>
            </tr>
            <?php
                $queryProdutos = 'SELECT * FROM produtos';
                $resultProdutos = mysqli_query($conn, $queryProdutos);

                while ($rowProdutos =  mysqli_fetch_assoc($resultProdutos)) {
                    echo "<tr>";
                    echo "<td>" . $rowProdutos['ID'] . "</td>";
                    echo "<td>" . $rowProdutos['Nome'] . "</td>";
                    echo  "<td>" . $rowProdutos['Valor'] . "</td>";
                    echo  "<td>" . $rowProdutos['Quantidade'] . "</td>";
                    echo  "<td>" . $rowProdutos['Tipo'] . "</td>";
                    echo "<td><a href='EditarProduto.php?ID=" . $rowProdutos['ID'] . "'>Editar</a> | <a href='ExcluirProduto.php?ID=". $rowProdutos['ID'] . "'>Excluir</a></td>";
                    echo "</tr>";
                    }
            ?>
        </table>
    </div>
    <style>
            table {
                border-collapse: collapse;
            }
            th, td {
                padding: 10px;
                border: 1px solid black;
                }

        </style>

    <script>
        function editar(event, idpedido) {
            event.preventDefault();

            if(confirm('Você deseja concluir o pedido?')){
                window.location.href = 'EditarProduto.php?IDPedido=' + idpedido;
            }
        }

        function excluir(event, idpedido) {
            event.preventDefault(); // Previne o comportamento padrão do link

            if (confirm("Você deseja excluir o pedido?")) {
                // Se o usuário confirmar a exclusão, redireciona imediatamente
                window.location.href = "ExcluirProduto.php?IDPedido=" + idpedido; 
            }
        }

    </script>
</body>
</html>