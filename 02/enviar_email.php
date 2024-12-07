<?php
include 'conectabanco.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $usuario_email = $_POST["usuario_email"];

  // Gerar um token único
  $token = bin2hex(random_bytes(32)); 

  // Armazenar o token no banco de dados (você precisará criar uma tabela para tokens)
  $sql = "INSERT INTO tokens_redefinicao (usuario_email, token) VALUES ('$usuario_email', '$token')";
  if ($conn->query($sql) === TRUE) {
    // Enviar email para o usuário com o link de redefinição
    $link = "http://localhost.com/redefinir_senha_form.php?token=" . $token;
    $mensagem = "Clique no link para redefinir sua senha: " . $link;
    mail($usuario_email, "Redefinição de Senha", $mensagem);

    echo "Um email foi enviado com instruções para redefinir sua senha.";
  } else {
    echo "Erro ao gerar token: " . $conn->error;
  }

  $conn->close();
}
?>