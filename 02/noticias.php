<?php

// Inclui o arquivo de conexão com o banco de dados
require_once("conectabanco.php");

// Função para criar e retornar a conexão
function getConexao()
{
    static $conexao = null; // Variável estática para manter a conexão aberta
    if ($conexao === null) {
        $conexao = new mysqli('localhost:3306', 'root', '', 'Noticias');
        if ($conexao->connect_error) {
            die("Erro na conexão: " . $conexao->connect_error);
        }
    }
    return $conexao;
}


// Função para listar notícias
function listarNoticias()
{
    $conexao = getConexao();
    $sql = "SELECT * FROM noticias";
    $result = $conexao->query($sql);

    if (!$result) {
        die("Erro na consulta: " . $conexao->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h3>" . htmlspecialchars($row["titulo"]) . "</h3>";
            echo "<p>" . htmlspecialchars($row["noticia"]) . "</p>";
            // Adiciona os botões "Editar" e "Excluir"
            echo "<a href='noticias.php?acao=editar&id=" . $row["id"] . "'>Editar</a>";
            echo " | ";
            echo "<a href='noticias.php?acao=excluir&id=" . $row["id"] . "'>Excluir</a>";
        }
    } else {
        echo "Nenhuma notícia encontrada.";
    }
}

// Função para mostrar uma notícia específica
function mostrarNoticia($id)
{
    $conexao = getConexao();

    $stmt = $conexao->prepare("SELECT * FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h2>" . htmlspecialchars($row["titulo"]) . "</h2>";
        echo "<p>" . htmlspecialchars($row["noticia"]) . "</p>";
    } else {
        echo "Notícia não encontrada.";
    }
}

function inserirNoticia($titulo, $noticia)
{
    $conexao = getConexao();

    if (empty($titulo) || empty($noticia)) {
        echo "Erro: Título e conteúdo são obrigatórios.";
        return;
    }

    $stmt = $conexao->prepare("INSERT INTO noticias (titulo, noticia) VALUES (?, ?)");

    if (!$stmt) {
        echo "Erro na preparação da consulta: " . $conexao->error;
        return;
    }

    $stmt->bind_param("ss", $titulo, $noticia);

    if ($stmt->execute()) {
        header("Location: mostrar_noticias.php");
        echo "Notícia inserida com sucesso!";
        exit;
    } else {
        echo "Erro ao inserir notícia: " . $stmt->error;
    }

    $stmt->close();
}

// Função para editar uma notícia existente
function editarNoticia($id, $titulo, $noticia)
{
    $conexao = getConexao();

    if (empty($titulo) || empty($noticia) || empty($id)) {
        echo "Erro: Todos os campos são obrigatórios.";
        return;
    }

    $stmt = $conexao->prepare("UPDATE noticias SET titulo = ?, noticia = ? WHERE id = ?");
    $stmt->bind_param("ssi", $titulo, $noticia, $id);

    if ($stmt->execute()) {
        header("Location: mostrar_noticias.php");
        echo "Notícia atualizada com sucesso!";
        exit;
    } else {
        header("Location: mostrar_noticias.php");
        echo "Erro ao atualizar notícia.";
        exit;
    }
}

// Função para excluir uma notícia
function excluirNoticia($id)
{
    $conexao = getConexao();

    if (empty($id)) {
        echo "Erro: ID inválido.";
        return;
    }

    $stmt = $conexao->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: mostrar_noticias.php");
        echo "Notícia excluída com sucesso!";
        exit;
    } else {
        header("Location: mostrar_noticias.php");
        echo "Erro ao excluir notícia.";
        exit;
    }
}

if (isset($_GET['acao']) && $_GET['acao'] == 'inserirNoticia') {
    $titulo = $_POST['titulo'];
    $noticia = $_POST['noticia'];
    inserirNoticia($titulo, $noticia);
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($acao == 'editar' && $id) {
        // Busca a notícia no banco de dados (utilizando prepared statement)
        $stmt_noticia = $conn->prepare("SELECT * FROM noticias WHERE id = ?");
        $stmt_noticia->bind_param("i", $id);
        $stmt_noticia->execute();
        $result = $stmt_noticia->get_result();
        $noticia = $result->fetch_assoc();
        $stmt_noticia->close(); // Fecha o prepared statement

        // Exibe o formulário de edição
?>

        <head>
            <link rel="stylesheet" href="styles/style.css">
        </head>
        <header>
            <h1>Editar noticia</h1>
            <nav>
                <ul>
                    <li><a href="index.html" aria-label="Ir para página inicial">Home</a></li><br>
                    <li><a href="mostrar_noticias.php" aria-label="Página de Notícias">Notícias</a></li><br>
                    <li><a href="inserirNoticia.php" aria-label="Inserir nova notícia">Inserir nova notícia</a></li><br>
                    <li><a href="fazerLogin.php" aria-label="Fazer Login">Login</a></li><br>
                    <li><a href="criarConta.php" aria-label="Criar nova conta">Criar nova conta</a></li><br>
                    <li><a href="redefinir_senha.php" aria-label="Redefinir Senha">Redefinir Senha</a></li><br>
                    <li><a href="about.html" aria-label="Ir para informações sobre o site">Sobre</a></li><br>
                </ul>
            </nav>
        </header>

        <main class="principal">
            <div class="wrapper">
                <span class="bg-animate"></span>
                <span class="bg-animate2"></span>
                <div class="form-box login">
                    <h2 class="animation" style="--i:0; --j:21;">Editar noticia:</h2>
                    <form id="dataForm" action="noticias.php?acao=atualizar" method="post">
                        <div class="input-box animation" style="--i:1; --j:22;">
                            <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
                            <i class='bx bxs-user'></i>
                        </div>
                        <div class="input-box animation" style="--i:2; --j:23;">
                            <input type="text" name="titulo" value="<?php echo $noticia['titulo']; ?>">
                            <i class='bx bxs-lock-alt'></i>
                        </div>
                        <div  class="input-box animation" style="--i:3; --j:24;">
                            <input type="text" name="noticia" value="<?php echo $noticia['noticia']; ?>">
                            <i class='bx bxs-lock-alt'></i>
                        </div>
                        <button type="submit" class="btn animation" style="--i:4; --j:25;">Atualizar</button>
                    </form>
                    </form>
                </div>
            </div>
        </main>
        <footer>
            <p>&copy; 2024 Meu Site</p>
        </footer>

<?php

    } elseif ($acao == 'excluir' && $id) {
        excluirNoticia($id);
        echo "Notícia excluída com sucesso!";
    } elseif ($acao == 'atualizar') {
        // Lógica para atualizar a notícia no banco de dados
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $noticia = $_POST['noticia'];
        editarNoticia($id, $titulo, $noticia);
        echo "Notícia atualizada com sucesso!";
    } elseif ($acao == 'inserirNoticia') {
        $titulo = $_POST['titulo'];
        $noticia = $_POST['noticia'];
        inserirNoticia($titulo, $noticia);
    }
}

// Chama a função para listar as notícias (apenas se não houver ação)
if (!isset($_GET['acao'])) {
    listarNoticias();
}

// Fechar a conexão apenas no final do script 
register_shutdown_function(function () {
    $conexao = getConexao();
    if ($conexao) {
        $conexao->close();
    }
});

?>