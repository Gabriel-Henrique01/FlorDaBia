<?php 
    session_start();
    include '../conexao.php';

    if(!isset($_SESSION["IDAdmin"]) && empty($_SESSION["IDAdmin"])){
        header("Location: ../index.php");
    } 

    // Verifica se o ID está sendo passado via GET
    if(isset($_GET['ID']) && !empty($_GET['ID'])) {
        $id = $_GET['ID'];
        $queryProduto = 'SELECT * FROM produtos WHERE ID = '. $id;
        $result = mysqli_query($conn, $queryProduto);
        $row = mysqli_fetch_assoc($result);
    }

    // Verifica se o formulário foi enviado e processa a atualização
    if (isset($_POST['Submit'])) {
        // Pega o ID enviado via POST
        $id = $_POST['ID'];
        $nome = $_POST['Nome'];
        $preco = $_POST['Valor'];
        $quantidade = $_POST['Quantidade'];
        $tipo = $_POST['Tipo'];

        // Atualiza os dados no banco de dados
        $update = "UPDATE produtos SET Nome = '$nome', Valor = '$preco', Quantidade = '$quantidade', Tipo = '$tipo' WHERE ID = '$id'";
        if (mysqli_query($conn, $update)) {
            echo "<script>alert('Produto atualizado com sucesso!');</script>";
            echo "<script>location.href='Produtos.php?mensagem=Produto atualizado com sucesso!';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar produto!');</script>";
            echo "<script>location.href='Produtos.php?mensagem=Erro ao atualizar produto!';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="../img/Logo.png">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/ocasioes.css">
    <title>Edição Produtos</title>
    <link rel="stylesheet" href="../css/Admin/EditarProduto.css">
</head>
<body>
  
    <!--MENU-->
    <header>
        <nav class="nav-bar">
            <div class="header">
                <<a href="index.php">
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
        <form action="EditarProduto.php?ID=<?php echo $row['ID']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $row['ID']; ?>" id="ID" name="ID" readonly />
            <input class="coiso" type="text" value="<?php echo $row['Nome']; ?>" id="Nome" name="Nome" />
            <input class="coiso" type="number" value="<?php echo $row['Valor']; ?>" id="Valor" name="Valor" step="0.01" />
            <input class="coiso" type="number" value="<?php echo $row['Quantidade']; ?>" id="Quantidade" name="Quantidade" />

            <!-- O campo file não pode ter value, ele deve ser usado apenas para upload de nova imagem -->
            <input class="coiso" type="file" id="imagem" name="imagem" />

            <!-- Campo select para o tipo -->
            <select class="coiso" name="Tipo" id="Tipo" required>
                <option value="" disabled>Escolha o tipo de produto</option>
                <option value="Flor" <?php if ($row['Tipo'] == 'Flor') echo 'selected'; ?>>Flor</option>
                <option value="Itens de jardinagem" <?php if ($row['Tipo'] == 'Itens de jardinagem') echo 'selected'; ?>>Itens de jardinagem</option>
                <option value="Buques" <?php if ($row['Tipo'] == 'Buques') echo 'selected'; ?>>Buques</option>
                <option value="Cestas" <?php if ($row['Tipo'] == 'Cestas') echo 'selected'; ?>>Cestas</option>
            </select>

            <input class="botao" type="submit" value="Atualizar" name="Submit">
        </form>
    </div>
</body>
</html>