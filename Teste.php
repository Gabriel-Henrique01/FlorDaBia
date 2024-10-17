<?php 
    session_start();
    include "conexao.php";

    $query = "SELECT DISTINCT ocasiao FROM ocasioes";
    $result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="website icon" type="png" href="img/Logo.png">
    <title>Flor da Bia</title>
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
                       <a href="Catalogo.php" style="text-decoration: none;"><p class="escrita-header">Produtos</p></a>
                        <img class="icon" src="img/flor-icon.svg" alt="Ícone de Produtos">
                    </div>
                    <div class="supremo">
                        <p class="escrita-header linha">|</p>
                    </div>
                    <div class="supremo">
                        <a href="#" onclick="toggleOcasiões(event);"> <p class="escrita-header">Ocasiões</p></a>
                        <img class="icon" src="img/ocasioes.svg" alt="Ícone de Ocasiões">
                        <div id="dropdownOcasiões" style="display: none;">
                            <?php 

                                session_start();
                                include "conexao.php";

                                $query = "SELECT DISTINCT ocasiao FROM ocasioes";
                                $result = mysqli_query($conn, $query);
                                
                                $ocasioes = [];
                                // Agrupa as ocasiões únicas em um array
                                while($r = mysqli_fetch_assoc($result)) {
                                    $ocasioes[] = $r['ocasiao'];
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

                    <script>
                        function toggleOcasiões(event) {
                            event.preventDefault(); // Evita que o link siga para outra página
                            const dropdown = document.getElementById("dropdownOcasiões");
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


      <!--PRIMEIRO CARROSEL-->

      <div class="carousel">
        <div class="carousel-inner">
            <img src="img/Foto 1.jpeg" alt="Imagem 1">
            <img src="img/Foto 1.jpeg" alt="Imagem 1">
            <img src="img/Foto 1.jpeg" alt="Imagem 1">
            <img src="img/Foto 1.jpeg" alt="Imagem 1">
            
          
        </div>
        <button class="prev botaoseta" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next botaoseta" onclick="moveSlide(1)">&#10095;</button>
    </div>
    
    
  <!-- Seção Sobre Nós -->
<section class="sobre-nos">
    <div class="sobre-nos-container">
        <h2>Sobre nós</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec eros ut tempor ultrices. Morbi ut odio vel massa vehicula.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget tortor a velit egestas viverra.</p>
    </div>
</section>

<!-- Seção de Localização -->
<section class="localizacao">
    <h2>Localização</h2>
    <div class="localizacao-container">
        <div class="mapa">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14638.181484627177!2d-46.876501549692875!3d-23.476858061724325!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cf03aaf6d062af%3A0x2c22de58cd7f17f1!2sAlphaville%2C%20Santana%20de%20Parna%C3%ADba%20-%20SP%2C%2006542-115!5e0!3m2!1spt-BR!2sbr!4v1726749840447!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" margin="0px 50px"></iframe>
        </div>
        <div class="contato">
            <h3>Nosso Endereço</h3>
            <p>Alameda Campinas, N:1867</p>
            <p>Bairro Alphaville</p>
            <p>São Paulo</p>
            <p>Telefone: <strong>11 99208-7823</strong></p>
            <p>Email: <strong>flordabia@flower.com</strong></p>
            <div class="social-media">
                <p>Siga-nos:</p> <!-- O texto será centralizado acima dos ícones -->
                <div class="icons">
                    <a href="#"><img src="img/faceicon - Copia.png" alt="Facebook"></a>
                    <a href="#"><img src="img/instagram-logo-480 - Copia.png" alt="Instagram"></a>
                    <a href="#"><img src="img/whatsappicon.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </div>
</section>



    <!-- FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <a href="index.html"><img src="img/logo.png" alt="Logo"></a>
            </div>
            <div class="footer-columns">
                <div class="footer-column">
                    <h3>Menu</h3>
                    <ul class="footer-list">
                        <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="catalogoflores.html" class="nav-link">Produtos</a></li>
                        <li class="nav-item"><a href="ocasioesromanticas.html" class="nav-link">Ocasiões</a></li>
    
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
    <script src="js/carroselhome.js"></script>
</body>
</html>
