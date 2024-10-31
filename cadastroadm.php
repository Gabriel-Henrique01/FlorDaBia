<?php
session_start();
include 'conexao.php';

// Verifica se a sessão IDAdmin está setada
if (!isset($_SESSION["IDAdmin"]) || empty($_SESSION["IDAdmin"])) {
    header("Location: cadastrocliente.php");
    exit(); // Sempre use exit após um redirecionamento
}

// Seu código de inserção e lógica aqui
if (isset($_POST["envio"])) {
    $nome = $_POST["nome"];
    $telefone = preg_replace('/\D/', '', $_POST["telefone"]);
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $local = $_POST["local"];

    $insert = "INSERT INTO `admin` (`Nome`, `Telefone`, `Email`, `Senha`, `Local`) VALUES ('$nome', '$telefone', '$email', '$senha', '$local')";

    if ($conn->query($insert) === TRUE) {
        $sql = "SELECT * FROM admin WHERE Email = '$email' AND Senha = '$senha'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["IDAdmin"] = $row["IDAdmin"];
            $_SESSION["Nome"] = $row["Nome"];
            $_SESSION["Local"] = $row["Local"];
            
            // Redirecionar para Admin.php
            header("Location: Admin/Admin.php");
            exit(); // Para garantir que o script não continue
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Erro: " . $insert . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="website icon" type="png" href="img/Logo.png">
    <link rel="stylesheet" href="css/cadastroadm.css">

    <title>Cadastra Administração</title>
    <link rel="stylesheet" href="css/style.css">

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
                    </div>
                    <div class="supremo">
                        <p class="escrita-header linha">|</p>
                    </div>
                    <div class="supremo" style="margin-right: 10%;">
                        <?php 
                            if (isset($_SESSION["IDAdmin"]) && !empty($_SESSION["IDAdmin"])) {
                                $nome = $_SESSION["Nome"];
                                echo "
                                        <a href='Admin/Admin.php'> <p>" . $nome . "</p> </a>
                                        <a href='logout.php' style='margin-left: 30px'> <p> Desconectar </p> </a>
                                    ";
                            }
                            elseif(isset($_SESSION["Nome"])) {
                                $nome = $_SESSION["Nome"];
                                echo "
                                        <a href='perfil.php'> <p>" . $nome . "</p> </a>
                                        <a href='logout.php' style='margin-left: 30px'> <p> Desconectar </p> </a>
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
   
   
    <div class="forms">
        <div class="container">
            <h1>Cadastro</h1>
            <form id="formCadastro" action="" method="POST">
                <input type="text" name="nome" placeholder="Nome:" required>
                <input type="tel" name="telefone" id="tell" class="tell" placeholder="Telefone:" oninput="formatarNumero(this)" required>
                <input type="email" name="email" placeholder="E-mail:" required>
                <input type="text" name="local" placeholder="Localização:" required>
                <input type="password" id="senha" name="senha" placeholder="Senha:" maxlength="20" required>
                <input type="password" id="senha1" name="senha1" placeholder="Confirme a senha:" maxlength="20" required>
                <input class="btn" type="submit" value="Cadastrar" name="envio">
            </form>
        </div>
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
        <script src="js/cadastro.js"></script>
        <script src="js/formatacao.js"></script>



</body>
</html>
