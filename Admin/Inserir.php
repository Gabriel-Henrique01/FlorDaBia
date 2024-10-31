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
    <title>Inserir produtos</title>
    <link rel="stylesheet" href="../css/Admin/Inserir.css">
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

    <div class="container">
        <form action="Inserir.php" method="post" enctype="multipart/form-data">
            <input class="coiso" type="text" name="nome" placeholder="Nome" required>
            <input class="coiso" type="number" step="0.01" name="valor" placeholder="Valor" required>
            <input class="coiso" type="number" name="quantidade" placeholder="Quantidade" required>
            <input class="coiso" type="file" name="img" id="img" accept="image/*" required>

            <!-- Novo campo para escolher o tipo de produto -->
            <select class="coiso" name="tipo" required>
                <option value="" disabled selected>Escolha o tipo de produto</option>
                <option value="Flor">Flor</option>
                <option value="Itens de jardinagem">Itens de jardinagem</option>
                <option value="Buques">Buques</option>
                <option value="Cestas">Cestas</option>
            </select>

            <input class="botao" type="submit" value="Enviar" name="enviar">
        </form>
    </div>
    <?php 

    if(isset($_POST['enviar'])){
        $Nome = $_POST['nome'];
        $Valor = $_POST['valor'];
        $Quantidade = $_POST['quantidade'];
        $tipo = $_POST['tipo']; // Captura o tipo selecionado

        if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
            // Define o caminho da pasta onde a imagem será salva
            $imgFolder = dirname(__FILE__) . "/../img/";  // Essa linha indica aonde essa imagem deve ser salva
    
            $imgName = $_FILES['img']['name'];  // Nome do arquivo original
            $fileName = preg_replace('/[^a-zA-Z0-9\-\_\.]/', '_', basename($imgName)); 

            /* ^
                Na linha acima, estou pegando o nome do arquivo, não da onde ele esta vindo, exemplo do arquivo FlorDaBia/Img/Logo.png ele só
                vai trazer Logo.png, ele tambem permite numeros maiusculos e minusculos, -,_ e . e caso exista espaço no nome do arquivo como 
                Logo Flor Da Bia.png, ele ira substituir para Logo_Flor_Da_Bia.png, tudo que não estiver nessa lista de caracteris vai ser
                substituido por _, ele tambem permite numeros
            */
            
            // Caminho completo para salvar o arquivo
            $imgPath = $imgFolder . $fileName;
    
            // Move o arquivo do local temporário para a pasta desejada
            if (move_uploaded_file($_FILES['img']['tmp_name'], $imgPath)) {
                // Salva o caminho relativo no banco de dados
                $imgDbPath = "img/" . $fileName;  // Caminho relativo para salvar no banco de dados
                
                $sql = "INSERT INTO `produtos` (`Nome`, `Valor`, `Quantidade`, `Img`, `Tipo`) VALUES ('$Nome', '$Valor', '$Quantidade', '$imgDbPath', '$tipo')";
                
                if ($conn->query($sql) === TRUE) {
                } else {
                    echo "Erro: " . $sql . "<br>" . $conn->error;
                }
    
            } else {
                echo "Erro ao mover o arquivo para a pasta.";
            }
        } else {
            echo "Erro no envio do arquivo.";
        }
        
    }
    ?>
</body>
</html>