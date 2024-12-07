<?php

require_once("conectabanco.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $titulo = $_POST["titulo"];
  $noticia = $_POST["noticia"];

  $sql = "INSERT INTO noticias (titulo, noticia, usuario) VALUES (?, ?)"; 

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $titulo, $noticia); 

  if ($stmt->execute()) {
    echo "Notícia salva com sucesso!";
  } else {
    echo "Erro ao salvar notícia: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}

?>