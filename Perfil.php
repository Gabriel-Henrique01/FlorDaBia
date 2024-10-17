<?php 
    session_start();
    include "conexao.php";

    if(!isset($_SESSION['IDCliente'])) {
        header('Location: Login.php');
        exit;
    } 

    if(isset($_GET['Tipo'])) {
        $Tipo = $_GET['Tipo'];

        $query = "SELECT * FROM produtos WHERE Tipo = '$Tipo'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

    } 


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="website icon" type="png" href="img/Logo.png">
    <link rel="stylesheet" href="css/header.css">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/Perfil1.css">
</head>
<body>
<header>
        <nav class="nav-bar">
            <div class="header">
                <a href="index.php">
                    <div class="logo-header">
                        <img class="logo" src="img/logo.png" alt="Logo">
                    </div>
                </a>
                <div class="menu-icon" onclick="toggleMenu()">
                    <img id="menuToggle" src="img/iconmenu.svg" alt="Menu">
                </div>
                <div class="sessoes-header" id="menuItems">
                    <div class="supremo" style="margin-left: 10%;">
                        <div class="dropdown">
                            <a href="Catalogo.php" onclick="toggleProdutos(event);" style="text-decoration: none;"><p class="escrita-header">Produtos</p></a>
                            <img class="icon" src="img/flor-icon.svg" alt="Ícone de Produtos">
                            <div id="dropdownProdutos" class="dropdown-menu" style="display: none;">
                                <?php

                                    $query2 = "SELECT DISTINCT Tipo FROM produtos";
                                    $result2 = mysqli_query($conn, $query2);
                                    
                                    $tipo = [];
                                    // Agrupa as ocasiões únicas em um array
                                    while($r = mysqli_fetch_assoc($result2)) {
                                        $tipo[] = $r['Tipo'];
                                    }
                                    
                                    // Exibe as ocasiões únicas com o dropdown redirecionando
                                    foreach($tipo as $Tipo) {
                                        echo '<details>';
                                        echo '<summary onclick="window.location.href=\'Catalogo.php?Tipo=' . urlencode($Tipo) . '\'">' . $Tipo . '</summary>';
                                        echo '</details>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="supremo">
                        <p class="escrita-header linha">|</p>
                    </div>
                    <div class="supremo">
                        <div class="dropdown">
                            <a href="#" onclick="toggleOcasiões(event);"> <p class="escrita-header">Ocasiões</p></a>
                            <img class="icon" src="img/ocasioes.svg" alt="Ícone de Ocasiões">
                            <div id="dropdownOcasiões" class="dropdown-menu" style="display: none;">
                                <?php

                                    $query3 = "SELECT DISTINCT ocasiao FROM ocasioes";
                                    $result3 = mysqli_query($conn, $query3);
                                    
                                    $ocasioes = [];
                                    // Agrupa as ocasiões únicas em um array
                                    while($row3 = mysqli_fetch_assoc($result3)) {
                                        $ocasioes[] = $row3['ocasiao'];
                                    }
                                    
                                    // Exibe as ocasiões únicas com o dropdown redirecionando
                                    foreach($ocasioes as $ocasiao) {
                                        echo '<details>';
                                        echo '<summary onclick="window.location.href=\'Ocasioes.php?ocasiao=' . urlencode($ocasiao) . '\'">' . $ocasiao . '</summary>';
                                        echo '</details>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleOcasiões(event) {
                            event.preventDefault(); // Evita que o link siga para outra página
                            const dropdown = document.getElementById("dropdownOcasiões");
                            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none"; // Alterna a visibilidade
                        }
                        function toggleProdutos(event) {
                            event.preventDefault(); // Evita que o link siga para outra página
                            const dropdown = document.getElementById("dropdownProdutos");
                            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none"; // Alterna a visibilidade
                        }
                    </script>

                    <div class="supremo">
                        <p class="escrita-header linha">|</p>
                    </div>
                    <div class="supremo">
                        <a href="carrinho.php"><p class="escrita-header">Carrinho</p></a>
                        <img class="icon" src="img/carrinho.svg" alt="Ícone de Carrinho">
                    </div>
                    <div class="supremo">
                        <p class="escrita-header linha">|</p>
                    </div>
                    <div class="supremo" style="margin-right: 10%;">
                        <?php 
                            if(isset($_SESSION["Nome"])) {
                                $nome = $_SESSION["Nome"];
                                echo "
                                        <a href='perfil.php'> <p>" . $nome . "</p> </a>
                                    ";
                            } else {
                                echo "<a href='Login.php'><p>Login</p></a>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
     <h1>Historico de pedidos </h1>
    <div class="container">
    <table>
        <tr>
            <th>N° Pedido</th>
            <th>Data</th>
            <th>Itens Qtd.</th>
            <th>Valor</th>
            <th>Status</th>
        </tr>
        <tr>
            <?php 
                $IDCliente = $_SESSION['IDCliente'];

                $queryPedido = "SELECT * FROM pedido WHERE IDCliente =" . $IDCliente;
                $resultPedido = mysqli_query($conn, $queryPedido);
                while($rowPedido = mysqli_fetch_assoc($resultPedido)){

                    echo "
                        <tr>
                        <td>" . $rowPedido['IDPedido'] . "</td>
                        <td>" . $rowPedido['Data'] . "</td>
                    ";

                    $queryProcessamento = "SELECT * FROM produtospedidos WHERE IDPedido = " . $rowPedido['IDPedido'];
                    $resultProcessamento = mysqli_query($conn, $queryProcessamento);

                    echo "<td>";
                    while( $rowProcessamento = mysqli_fetch_assoc($resultProcessamento)) {
                        $queryProdutos = 'SELECT * FROM produtos WHERE ID = ' . $rowProcessamento['IDProduto'];
                        $resultProdutos = mysqli_query($conn, $queryProdutos);
                        $rowProdutos = mysqli_fetch_assoc($resultProdutos);

                        echo $rowProdutos['Nome'] . ' (' . $rowProcessamento['Quantidade'] . ') <br>';
                        
                    }
                    echo "</td>";
                    echo "<td>" . $rowPedido['PrecoTotal'] . "</td>";
                    echo "<td>" . $rowPedido['Status'] . "</td>";
                    echo '</tr>';
                }
                
            ?>
        </tr>
    </table>
    </div>
    <!-- FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <a href="index.php"><img src="img/logo.png" alt="Logo"></a>
            </div>
            <div class="footer-columns">
                <div class="footer-column">
                    <h3>Menu</h3>
                    <ul class="footer-list">
                            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                            <li class="nav-item"><a href="catalogo.php" class="nav-link">Produtos</a></li>
                            <li class="nav-item"><a href="ocasioes.php" class="nav-link">Ocasiões</a></li>
        
                    </ul>
            </div>
                <div class="footer-column">
                    <h3>Contato</h3>
                    <p>Email: flordabia@flower.com</p>
                    <p>Telefone: +55 (11) 99208-7823</p>
                </div>
                <div class="footer-column">
                    <h3>Siga-nos</h3>
                    <div class="footer-social">
                        <a href="#"><img src="img/faceicon - Copia.png" alt="Facebook"></a>
                        <a href="https://www.instagram.com/invollve.com.br/"><img src="img/instagram-logo-480 - Copia.png" alt="Instagram"></a>
                        <a href="https://wa.me/+555511965822333?text=Ol%C3%A1"><img src="img/whatsappicon.png" alt="WhatsApp"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Flor da Bia. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="js/menu.js"></script>


</body>
</html>