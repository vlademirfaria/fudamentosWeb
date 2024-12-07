<?php

require_once("conectabanco.php");

function fazerLogin($usuario, $senha)
{
    global $conn;

    // Consulta o banco de dados usando uma declaração preparada
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifica se a senha está correta
        if (password_verify($senha, $row['senha'])) {
            session_start();
            // Redireciona para a página index.html
            header("Location: index.html");
            $_SESSION["usuario"] = $usuario;
            echo "Login realizado com sucesso!";
            exit;
        } else {
            header("Location: fazerLogin.php");
            echo "Senha incorreta.";
            exit;
        }
    } else {
        echo "Usuário não encontrado.";
        header("Location: fazerLogin.php");
        exit;
    }
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["acao"]) && $_GET["acao"] == "fazerLogin") {
    $usuario = $_POST["usuario"]; // Obtém o nome de usuário do formulário
    $senha = $_POST["senha"]; // Obtém a senha do formulário

    fazerLogin($usuario, $senha); // Chama a função de login
    $conn->close();
}
