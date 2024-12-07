<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha</title>
  <link rel="stylesheet" href="styles/style.css">
  <link rel="icon" type="image/icon" href="icon/favicon.ico">
</head>

<body>
  <header>
    <h1>Redefinir senha</h1>
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
      <span class="bg-animate"></span>
      <span class="bg-animate2"></span>
      <div class="form-box login">
        <h2 class="animation" style="--i:0; --j:21;">Redefinir Senha:</h2>
        <form id="dataForm" action="enviar_email.php" method="post">
          <div class="input-box animation" style="--i:1; --j:22">
            <input type="text" id="usuario_email" name="usuario_email" required><br><br>
            <label for="usuario_email">Usuário ou Email:</label>
            <i class="bx bxs-user"></i>
          </div>
          <input type="submit" value="Enviar Email">
        </form>
      </div>
    </div>
  </main>
  <footer>
    <p>&copy; 2024 Meu site</p>
  </footer>
  <script src="scripts/script.js" defer></script>
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