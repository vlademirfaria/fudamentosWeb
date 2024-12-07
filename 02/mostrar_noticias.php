<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/icon" href="icon/favicon.ico">
</head>

<body>
    <header>
        <h1>Notícias</h1>
        <nav>
            <ul id="menu">
                <li><a href="index.html" aria-label="Ir para página inicial">Home</a></li><br>
                <li><a href="mostrar_noticias.php" aria-label="Página de Notícias">Notícias</a></li><br>
                <li><a href="about.html" aria-label="Ir para informações sobre o site">Sobre</a></li><br>
            </ul>
        </nav>
    </header>
    <main class="principal">
        <div class="wrapper">
            <h2>Notícias</h2>
            <?php
            require_once("noticias.php");

            // Chama a função para listar as notícias
            listarNoticias();

            // Fecha a conexão com o banco de dados aqui
            $conn->close();
            ?>

        </div>
    </main>
    <footer>
        <p>&copy; 2024 Meu Site</p>
    </footer>
    <script>
        // Faz a requisição AJAX para verificar o status de login
        fetch('verificar_login.php')
            .then(response => response.json())
            .then(data => {
                const menu = document.getElementById('menu');
                if (data.logado) {
                    // Se estiver logado, adiciona o nome do usuário e o botão "Sair"
                    menu.innerHTML += `<li><p>Olá, ${data.usuario}!</p></li>`;
                    menu.innerHTML += `<li><a href="inserirNoticia.php" aria-label="Inserir nova notícia">Inserir nova notícia</a></li><br>`;
                    menu.innerHTML += `<li><a href='logout.php' aria-label="Sair">Sair</a></li><br>`;
                    menu.innerHTML += `<li><a href="redefinir_senha.php" aria-label="Redefinir Senha">Redefinir Senha</a></li><br>`;
                } else {
                    // Se não estiver logado, adiciona os links de login e cadastro
                    menu.innerHTML += `<li><a href='fazerLogin.php' aria-label='Fazer Login'>Login</a></li><br>`;
                    menu.innerHTML += `<li><a href='criarConta.php' aria-label='Criar nova conta'>Criar nova conta</a></li><br>`;
                }
            });
    </script>
</body>

</html>