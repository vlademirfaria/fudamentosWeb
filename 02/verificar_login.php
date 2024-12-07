<?php
session_start();
if (isset($_SESSION["usuario"])) {
    // Retorna o nome do usuário se estiver logado
    echo json_encode(['logado' => true, 'usuario' => $_SESSION["usuario"]]);
} else {
    // Retorna que não está logado
    echo json_encode(['logado' => false]);
}
?>