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
        $name = $_POST['nome'];
        $price = $_POST['valor'];
        $quantity = $_POST['quantidade'];
        $img = "img/".$_FILES['img']['name'];
        move_uploaded_file($_FILES['img']['tmp_name'], $img);
        
        // Adicionando o tipo de produto à consulta SQL
        $tipo = $_POST['tipo']; // Captura o tipo selecionado

        $sql = "INSERT INTO `produtos` (`Nome`, `Valor`, `Quantidade`, `Img`, `Tipo`) VALUES ('$name', '$price', '$quantity', '$img', '$tipo')";

        if ($conn->query($sql) === TRUE) {
            echo "Produto adicionado com sucesso!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>
</body>
</html>