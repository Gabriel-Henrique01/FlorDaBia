<?php
    session_start();
    include "conexao.php";

    // Verifica se o tipo foi passado como parâmetro
    if(isset($_GET['Tipo'])) {
        $Tipo = $_GET['Tipo'];

        $query = "SELECT * FROM produtos WHERE Tipo = '$Tipo'";
        $result = mysqli_query($conn, $query);

    } else {
        // Se não for passado, redireciona para a página principal ou exibe uma mensagem de erro
        header("Location: Catalogo.php?Tipo=Flor");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="website icon" type="png" href="img/Logo.png">
    <title><?php echo htmlspecialchars($Tipo); ?></title> <!-- O título será o tipo selecionado -->
    <link rel="stylesheet" href="css/catalogo.css">
    <style>
        .disabled {
            background-color: #008080;
            color: #fff;
            cursor: not-allowed;
            pointer-events: none; 
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    
    <!--MENU-->
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
 
    <!--Catalogo-->
    <div class="pesquisa">
        <input type="text" placeholder="Pesquisar">
        <img class="icon-lupa" src="img/pesquisa.svg">
    </div>
    <h1><?php echo htmlspecialchars($Tipo); ?></h1> <!-- Exibe o tipo no cabeçalho -->
    <div class="container">
        <?php 
        if (mysqli_num_rows($result) > 0) { // Verifica se há produtos
            while ($row = mysqli_fetch_assoc($result)) {
                $quantidade = $row['Quantidade'];
                $botaoTexto = $quantidade > 0 ? 'Comprar' : 'Item Indisponível';
                $botaoClasse = $quantidade > 0 ? '' : 'disabled';
                $link = $quantidade > 0 ? 'paginadecompra.php?ID='.$row["ID"] : '#';

                echo '<div class="card">';
                echo '<img src="'. htmlspecialchars($row['Img']) .'" alt="'. htmlspecialchars($row['Nome']) .'">';
                echo '<h2>'. htmlspecialchars($row['Nome']) .'</h2>';
                echo '<p>R$: '. htmlspecialchars($row['Valor']) .'</p>';
                echo '<a href="'.$link.'"><button class="' . $botaoClasse . '">' . $botaoTexto . '</button></a>';
                echo '</div>';
            }
        } else {
            echo '<p>Nenhum produto encontrado para o tipo "' . htmlspecialchars($Tipo) . '"</p>'; // Mensagem caso não haja produtos
        }
        ?>
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
