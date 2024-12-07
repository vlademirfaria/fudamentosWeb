<?php
// Inclui o arquivo de conexão com o banco de dados
require_once("conectabanco.php");
require_once("enviar_email.php");

function gerarToken($usuario)
{
    global $conn;
    // Gera um token aleatório
    $token = bin2hex(random_bytes(32));
    $dataExpiracao = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora

    // Salva o token no banco de dados
    $sql = "INSERT INTO tokens_redefinicao (usuario, token, data_expiracao) VALUES ('$usuario', '$token', '$dataExpiracao')";
    if ($conn->query($sql) === TRUE) {
        return $token;
    } else {
        echo "Erro ao gerar token: " . $conn->error;
        return false;
    }
    $conn->close();
}

// Função para enviar o email de redefinição de senha
function enviarEmailRedefinicao($usuario, $token)
{
    $linkRedefinicao = "http://localhost/redefinir_senha.php?token=" . $token;
    $assunto = "Redefinição de Senha";
    $mensagem = "Clique no link para redefinir sua senha: " . $linkRedefinicao;

    // Chama a função enviarEmail (de enviar_email.txt)
    if (enviarEmailRedefinicao($usuario, $assunto, $mensagem)) {
        echo "Email de redefinição enviado com sucesso!";
    } else {
        echo "Erro ao enviar email de redefinição.";
    }
    
}

// Função para verificar a validade do token
function verificarToken($token)
{
    global $conn;
    $sql = "SELECT * FROM tokens_redefinicao WHERE token = '$token'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifica se o token expirou
        if (strtotime($row['data_expiracao']) > time()) {
            return $row['usuario'];
        } else {
            echo "Token expirado.";
            return false;
        }
    } else {
        echo "Token inválido.";
        return false;
    }

}

// Função para atualizar a senha do usuário
function atualizarSenha($usuario, $novaSenha)
{
    global $conn;
    $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET senha = '$senhaHash' WHERE usuario = '$usuario'";
    if ($conn->query($sql) === TRUE) {
        echo "Senha atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar senha: " . $conn->error;
    }
    $conn->close();
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if (verificarToken($token)) {
        $usuario = verificarToken($token); // Obtém o usuário do token

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $novaSenha = $_POST["nova_senha"];

            // Validações da nova senha...

            atualizarSenha($usuario, $novaSenha);

            // Redireciona para a página de login após a atualização da senha
            header("Location: fazerLogin.php");
            exit;
        }
        

?>
        <form action="redefinir_senha.php?token=<?php echo $token; ?>" method="post">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required><br><br>
            <input type="submit" value="Redefinir Senha">
        </form>
<?php
    }
} else {
    echo "Token não fornecido.";
}


?>