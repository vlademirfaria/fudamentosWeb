window.onload = function () {
    const params = new URLSearchParams(window.location.search);
    const output = document.getElementById('output');

    if (params.has('name') && params.has('email') && params.has('age')) {
        output.innerHTML = `
            <p>Nome: ${params.get('name')}</p>
            <p>Email: ${params.get('email')}</p>
            <p>Idade: ${params.get('age')}</p>
        `;
    } else {
        // Exibição de mensagem de erro caso algum dado esteja faltando
        console.error("Um ou mais parâmetros estão faltando.");
        output.innerHTML = "Houve um erro ao carregar os dados.";
    }

    const wrapper = document.querySelector('.wrapper');
    const registerLink = document.querySelector('.register-link');
    const loginLink = document.querySelector('.login-link');

    registerLink.onclick = () => {
        wrapper.classList.add('active');
    };

    loginLink.onclick = () => {
        wrapper.classList.remove('active');
    };
};