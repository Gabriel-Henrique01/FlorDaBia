<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="website icon" type="png" href="img/FlorDaBia.png">
    <link rel="stylesheet" href="css/Index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Flor Da Bia</title>
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                
            </div>
            <div class="nav-center">
                <a href="FlorDaBia.html" id="logo"><img src="img/FlorDaBia.png"alt="Flor Da Bia" id="logo"></a>
            </div>
            <div class="nav-left">
                <div class="dropdown-menu">
                    <ul>
                        <li>
                            <details>
                                <summary>Catalogo</summary>
                                <p>Itens De Jardinagem</p>
                                <p>Buques</p>
                                <p>Cestas</p>
                            </details>
                        </li>
                        <li>
                            <details>
                                <summary>Ocasioes</summary>
                                <p>Romantica</p>
                                <p>Condolencias</p>
                                <p>Desculpas</p>
                                <p>Maternidade</p>
                                <p>Amizade</p>
                            </details>
                        </li>
                        <li>
                            <a href=""><img src="img/Carrinho.svg" alt="" id="CarrinhoCompras"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>






<br>
    <form action="FlorDaBia.php" method="post" enctype="multipart/form-data">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="number" step="0.01" name="valor" placeholder="Valor" required>
        <input type="number" name="quantidade" placeholder="Quantidade" required>
        <input type="file" name="img" id="img" accept="image/*" required>

        <!-- Novo campo para escolher o tipo de produto -->
        <select name="tipo" required>
            <option value="" disabled selected>Escolha o tipo de produto</option>
            <option value="Flor">Flor</option>
            <option value="Itens de jardinagem">Itens de jardinagem</option>
            <option value="Buques">Buques</option>
            <option value="Cestas">Cestas</option>
        </select>

        <input type="submit" value="Enviar" name="enviar">
    </form>

    <?php 

    include 'conexao.php';
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