<?php
session_start(); // Inicia a sessão
session_destroy(); // Destrói a sessão
header("Location: index.html"); // Redireciona para a página inicial
exit;
?>