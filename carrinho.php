<?php
session_start();
include "conexao.php";

if (!isset($_SESSION["IDCliente"])) {
    header("Location: Login.php");
    exit();
}

function consultaCep($cep) {
    $cep = preg_replace("/[^0-9]/", "", $cep);
    $url = "https://viacep.com.br/ws/$cep/json/";

    /* O cURL, ele é utilizado para fazer requisição à APIs, ele pode trabalhar com os formatos de arquivo, JSON e XML, ele trabalha com diversos
    protocolos, como HTTPS, HTTP, FTP  */

    $ch = curl_init($url);// inicia o cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//faz com que o resultado em JSON ou XML seja transformado em string, permitindo uma melhor manipulação
    $response = curl_exec($ch);//Faz a requisição e armazena as variaveis 
    curl_close($ch);// está finalizando o cURL

    return json_decode($response, true);//faz com que o que o arquivo JSON vire um array associativo
    
}

$cepData = [];

$rua = '';
$bairro = '';
$cidade = '';
$estado = '';
// Defina as variáveis fora do bloco condicional


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cep'])) {
    $cep = $_POST['cep'];
    $cepData = consultaCep($cep);

    // Atualizando as variáveis
    $rua = $cepData['logradouro'] ?? '';
    $bairro = $cepData['bairro'] ?? '';
    $cidade = $cepData['localidade'] ?? '';
    $estado = $cepData['uf'] ?? '';
    $numero = $_POST["numero"] ?? '';

    $_SESSION['endereco'] = [
        'cep' => $cep,
        'rua' => $rua,
        'bairro' => $bairro,
        'cidade' => $cidade,
        'estado' => $estado,
        'numero' => $numero
    ];

    
}

// Função para adicionar itens ao carrinho
function adicionarAoCarrinho($produtoId, $produtoNome, $quantidade, $preco, $imagem) {
    global $conn;
    
    // Verificar a quantidade disponível no estoque
    $sql = "SELECT Quantidade FROM produtos WHERE ID = '$produtoId'";
    $result = $conn->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        $quantidadeEstoque = $row['Quantidade'];

        // Ajustar a quantidade para o máximo disponível se necessário
        if ($quantidade > $quantidadeEstoque) {
            $quantidade = $quantidadeEstoque;
        }

        if (isset($_SESSION['carrinho'][$produtoId])) {
            $_SESSION['carrinho'][$produtoId]['quantidade'] += $quantidade;
            // Verificar se a quantidade no carrinho não excede o estoque
            if ($_SESSION['carrinho'][$produtoId]['quantidade'] > $quantidadeEstoque) {
                $_SESSION['carrinho'][$produtoId]['quantidade'] = $quantidadeEstoque;
            }
        } else {
            $_SESSION['carrinho'][$produtoId] = array(
                'nome' => $produtoNome,
                'quantidade' => $quantidade,
                'preco' => $preco,
                'imagem' => $imagem
            );
        }
    }
}

// Função para atualizar quantidade
function atualizarQuantidade($produtoId, $quantidade) {
    global $conn;
    
    // Verificar a quantidade disponível no estoque
    $sql = "SELECT Quantidade FROM produtos WHERE ID = '$produtoId'";
    $result = $conn->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        $quantidadeEstoque = $row['Quantidade'];

        // Garantir que a quantidade no carrinho não exceda o estoque
        if ($quantidade > $quantidadeEstoque) {
            $quantidade = $quantidadeEstoque;
        }

        if (isset($_SESSION['carrinho'][$produtoId])) {
            $_SESSION['carrinho'][$produtoId]['quantidade'] = $quantidade;
        }
    }
}

// Função para remover produto
function removerDoCarrinho($produtoId) {
    unset($_SESSION['carrinho'][$produtoId]);
}

// Manipulando ações do carrinho
if (isset($_GET['acao'])) {
    $produtoId = $_GET['id'];
    

    if ($_GET['acao'] == 'add') {
        $produtoNome = $_GET['nome'];
        $quantidade = $_GET['quantidade'];
        $preco = $_GET['preco'];
        $imagem = urldecode($_GET['img']);
        adicionarAoCarrinho($produtoId, $produtoNome, $quantidade, $preco, $imagem);
    } elseif ($_GET['acao'] == 'atualizar') {
        $novaQuantidade = $_GET['quantidade'];
        atualizarQuantidade($produtoId, $novaQuantidade);
    } elseif ($_GET['acao'] == 'remover') {
        removerDoCarrinho($produtoId);
    }

    // Redireciona para evitar múltiplas submissões
    header("Location: carrinho.php");
    exit();
}

// Processar finalização do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_pedido'])) {
    $endereco = $_SESSION['endereco'] ?? null;

    $rua = $endereco['rua'] ?? '';
    $bairro = $endereco['bairro'] ?? '';
    $cidade = $endereco['cidade'] ?? '';
    $estado = $endereco['estado'] ?? '';
    $cep = $endereco['cep'] ?? '';
    $numero = $endereco['numero'] ?? '';

    $IDCliente = $_SESSION["IDCliente"];

    $queryCliente = "SELECT * FROM cliente WHERE IDCliente = $IDCliente";
    $resultCliente = $conn->query($queryCliente);
    $rowCliente = mysqli_fetch_assoc($resultCliente);

    date_default_timezone_set('America/Sao_Paulo');
    $data = date('d-m-Y');
    $hora = date('H:i:s');
    $local = $_POST['city'];

    // Calcular o valor total do carrinho
    $total = 0;
    foreach ($_SESSION['carrinho'] as $produto) {
        $subtotal = $produto['quantidade'] * $produto['preco'];
        $total += $subtotal;
    }

    // Inserir o pedido com o valor total
    $InsertPedido = "INSERT INTO `pedido` (`IDCliente`, `Data`, `Horario`, `Status`, `PrecoTotal`, `Local`) 
                     VALUES ('$IDCliente', '$data', '$hora', 'Pendente', '$total', '$cidade')";

    if ($conn->query($InsertPedido) === TRUE) {
        $query = "SELECT * FROM pedido ORDER BY IDPedido DESC LIMIT 1";
        $result = $conn->query($query);
        $IDPedido = $result->fetch_assoc()["IDPedido"];

        $produtos = ""; // Variável para armazenar a mensagem dos produtos para o WhatsApp

        // Inserir os produtos do carrinho no pedido
        foreach ($_SESSION['carrinho'] as $produtoId => $produto) {
            $InsertProduto = "INSERT INTO `produtospedidos` (`IDPedido`, `IDProduto`, `Quantidade`, `PrecoAtual`) 
                              VALUES ('$IDPedido', '$produtoId', '{$produto['quantidade']}', '{$produto['preco']}')";
            $conn->query($InsertProduto);

            // Atualizar a quantidade de produtos no estoque
            $sql = "SELECT Quantidade FROM produtos WHERE ID = '$produtoId'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $quantidadeFinal = $row['Quantidade'] - $produto['quantidade'];
            $AtualizarQuantidade = "UPDATE produtos SET Quantidade = '$quantidadeFinal' WHERE ID = '$produtoId'";
            $conn->query($AtualizarQuantidade);

            // Adicionar os produtos à mensagem do WhatsApp
            $produtos .= "{$produto['quantidade']}x {$produto['nome']} - R$ {$produto['preco']}\n";

            unset($_SESSION['carrinho']);
        }
        
        // Formatando o valor total para exibição
        $totalFormatado = number_format($total, 2, ',', '.');

        

        

// No campo da mensagem do WhatsApp, certifique-se de que o `$numero` seja passado corretamente, no trecho "Endereço"
        $mensagem = '
            *Agradecemos pela Preferência*
            Estamos prontos para tornar seu dia mais florido. Nosso horário de atendimento é de segunda a sexta, das 8h às 18h, e aos sábados, domingos e feriados das 8h às 14h.
            Link do site
            ---------------------------------------
            Confira o pedido abaixo:
            N° Pedido: ' . $IDPedido . '
            ---------------------------------------

            Produtos  

            Subtotal: R$ ' . number_format($total, 2, ',', '.') . '
            Taxa de entrega não incluída
            Total: R$ ' . number_format($total, 2, ',', '.') . '

            ---------------------------------------

            Tempo de entrega: de 2 horas à 3 horas

            Cliente: ' . $rowCliente['Nome'] . ' 
            Telefone: ' . $rowCliente['Telefone'] . '
            Endereço: ' . $rua . ' Nº ' . $numero . ' - ' . ($rowCliente['Complemento'] ?? 'Sem complemento') . '
            Bairro: ' . $bairro . '
            Cidade: ' . $cidade . ' - ' . $estado . '
            CEP: ' . $cep . '

            Pagamento: Pix
            Nome da conta Pix: FlorDaBia
            Chave Pix: 46404501848

            Copie a chave e faça o pagamento através do Pix. O Flor Da Bia irá conferir o pagamento para liberação do seu pedido.

            Pedido gerado pelo Flor Da Bia às horas
        ';
        // Criar a URL para redirecionar ao WhatsApp

        $queryAdmin = "SELECT * FROM Admin WHERE Local = '$cidade'";
        $resultAdmin = mysqli_query($conn, $queryAdmin);
        $rowAdmin = mysqli_fetch_assoc($resultAdmin);

        $whatsappUrl = "https://wa.me/".$rowAdmin["Telefone"]."?text=" . rawurlencode($mensagem);
        //echo $mensagem;
        // Redirecionar para o WhatsApp
        header("Location: $whatsappUrl");
        exit();

    } else {
        echo "Erro ao finalizar o pedido.";
    }
}

if(isset($_GET['Tipo'])) {
    $Tipo = $_GET['Tipo'];

    $query = "SELECT * FROM produtos WHERE Tipo = '$Tipo'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

} 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="img/Logo.png">
    <title>Meu Carrinho</title>
    <link rel="stylesheet" href="css/carrinho.css">
    
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

<!--Carrinho-->
<div class="container">
    <div class="header">
      <h1>Meu Carrinho</h1>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Imagem</th>
            <th>Produto</th>
            <th>Valor</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        if (!empty($_SESSION['carrinho'])) { // Criar a estrutura do carrinho
            foreach ($_SESSION['carrinho'] as $produtoId => $produto) {
                $subtotal = $produto['quantidade'] * $produto['preco'];
                $total += $subtotal;
                echo "<tr>
                        <td><img src='{$produto['imagem']}' alt='{$produto['nome']}' class='product-image' style='width:100px; height:auto; background-size: cover;'></td>
                        <td>{$produto['nome']}</td>
                        <td>R$ {$produto['preco']}</td>
                        <td>
                            <form action='carrinho.php' method='get'>
                                <input type='hidden' name='acao' value='atualizar'>
                                <input type='hidden' name='id' value='{$produtoId}'>
                                <button type='submit' name='quantidade' value='".($produto['quantidade'] - 1)."' ".($produto['quantidade'] == 1 ? "disabled" : "").">-</button>
                                <span>{$produto['quantidade']}</span>
                                <button type='submit' name='quantidade' value='".($produto['quantidade'] + 1)."'>+</button>
                            </form>
                        </td>
                        <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
                        <td> 
                            <a href='carrinho.php?acao=remover&id={$produtoId}' style='text-decoration: none; color: black;'>Remover</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Seu carrinho está vazio.</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
        <div class="address-container">
            <h2>Informações do Endereço</h2>
            <form method="post" action="">
                <label for="cep">CEP:</label>
                <input type="text" name="cep" id="cep" placeholder="Digite o CEP" required>
                <label for="numero">Número:</label>
                <input type="text" name="numero" id="numero" placeholder="Digite o número do endereço" required>
                <button type="submit">Buscar</button>
            </form>

            <!-- Exibindo o endereço se a consulta ao CEP tiver retornado dados -->
            <?php if (!empty($cepData) && !isset($cepData['erro'])): ?>
                <label for="logradouro">Endereço:</label>
                <input type="text" id="logradouro" value="<?= $rua ?>" name="logradouro"><br>

                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" value="<?= $bairro ?>" name="bairro"><br>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" value="<?= $cidade ?>" name="cidade"><br>

                <label for="estado">Estado:</label>
                <input type="text" id="estado" value="<?= $estado ?>" name="estado"><br>

            <?php elseif (isset($cepData['erro'])): ?>
                <p>CEP não encontrado. Por favor, insira o endereço manualmente.</p>
            <?php endif; ?>
    </div>

    <div class="button-container">
        <!-- Formulário para finalizar o pedido e redirecionar ao WhatsApp -->
        <form action="carrinho.php" method="post">       
            <input type="hidden" name="finalizar_pedido" value="1">
            <button type="submit">Finalizar Pedido</button>
        </form>

        <p>Subtotal: R$: <?php echo number_format($total, 2, ',', '.'); ?></p>
    </div>

    <button class="choose-more" onclick="window.location.href='Catalogo.php'">
      Escolher mais produtos +
    </button>
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
