<?php
    session_start(); // Inicia a sessão
    session_unset(); // Remove todas as variáveis de sessão
    session_destroy(); // Destroi a sessão
    header("Location: ../Index.php"); // Redireciona o usuário para a página de login
    exit();
?>