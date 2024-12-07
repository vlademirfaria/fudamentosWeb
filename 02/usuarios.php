<?php

// Inclui o arquivo de conexão com o banco de dados
require_once("conectabanco.php");

// Função para listar usuários
function listarUsuarios()
{
  global $conn;
  $sql = "SELECT * FROM usuarios";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Exibe os usuários aqui, formatando como desejar
      echo "Nome Completo: " . $row["nome"] . "<br>";
      echo "Usuário: " . $row["usuario"] . "<br>";
      echo "Email: " . $row["email"] . "<br>";
    }
  } else {
    echo "Nenhum usuário encontrado.";
  }
}

// Função para criar uma nova conta de usuário
function criarConta($usuario, $nome, $Dt_nasc, $email, $senha)
{
  global $conn;

  // Hash da senha para segurança
  $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

  $sql = "INSERT INTO `usuarios` (usuario, nome, Dt_nasc, email, senha) VALUES ('$usuario', '$nome', '$Dt_nasc', '$email', '$senhaHash')";

  if ($conn->query($sql) === TRUE) {
    // Redireciona para a página de login
    header("Location: fazerLogin.php");
    echo "Usuário criado com sucesso!";
    exit; // Encerra a execução do script após o redirecionamento
  } else {
    echo "Erro ao criar usuário: " . $conn->error;
  }
}

// Função para excluir um usuário
function excluirUsuario($usuario)
{
  global $conn;
  $sql = "DELETE FROM usuarios WHERE usuario = $usuario";

  if ($conn->query($sql) === TRUE) {
    echo "Usuário excluído com sucesso!";
  } else {
    echo "Erro ao excluir usuário: " . $conn->error;
  }
}

// Função para salvar um usuário (criar ou atualizar)
function salvarUsuario($usuario, $nome, $Dt_nasc, $email, $senha)
{
  global $conn;

  // Hash da senha para segurança
  $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

  if ($usuario) {
    // Atualizar usuário existente
    $sql = "UPDATE usuarios SET usuario='$usuario', senha='$senhaHash' WHERE id_usuario=$usuario";
  } else {
    // Criar novo usuário
    $sql = "INSERT INTO usuarios (usuario, nome, Dt_nasc, email, senha) VALUES ('$usuario', '$nome', '$Dt_nasc', '$email', '$senhaHash')";
  }

  if ($conn->query($sql) === TRUE) {
    echo "Usuário salvo com sucesso!";
  } else {
    echo "Erro ao salvar usuário: " . $conn->error;
  }
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["acao"]) && $_GET["acao"] == "criar") {
  // Obtém os dados do formulário
  // Obtém os dados do formulário
  $usuario = $_POST["usuario"];
  $nome = $_POST["nome"]; // Recebe o nome do formulário
  $Dt_nasc = $_POST["Dt_Nasc"]; // Recebe a data de nascimento do formulário
  $email = $_POST["email"]; // Recebe o email do formulário
  $senha = $_POST["senha"];

  // Validações
  $erros = array();

  // Validação do usuário
  if (strlen($usuario) < 5) {
    $erros[] = "O nome de usuário deve ter no mínimo 5 caracteres.";
  }
  if (strlen($usuario) > 20) {
    $erros[] = "O nome de usuário deve ter no máximo 20 caracteres.";
  }
  if (!preg_match("/^[a-zA-Z0-9_-]+$/", $usuario)) {
    $erros[] = "O nome de usuário deve conter apenas letras, números e os caracteres '_' e '-'.";
  }

  // Validação da senha
  if (strlen($senha) < 8) {
    $erros[] = "A senha deve ter no mínimo 8 caracteres.";
  }

  // Se houver erros, exibe as mensagens de erro
  if (!empty($erros)) {
    foreach ($erros as $erro) {
      echo $erro . "<br>";
    }
  } else {
    // Cria a conta do usuário
    criarConta($usuario, $nome, $Dt_nasc, $email, $senha);
    
  }
}
