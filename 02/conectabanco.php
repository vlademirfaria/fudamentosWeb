<?php
global $servername ;
global $username;
global $password;
global $database;

$servername = "localhost:3306";
$username = "root";
$password = "";
$database = "Noticias";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verifica a conexão
if ($conn->connect_error)  
 {
  die("Falha na conexão: " . $conn->connect_error);
}
?>