<?php 
    include "conexao.php";

    // Captura o termo de busca do POST
    $termoBusca = $_POST['Pesquisa'] ?? '';

    // Consulta no banco de dados
    $sql = "SELECT * FROM produtos WHERE nome LIKE '%$termoBusca%'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
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
        echo '<p>Nenhum produto encontrado.</p>';
    }

    $conn->close();
?>
