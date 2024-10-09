// Monitora a atividade do usuário
var inactivityTime = 900; // Tempo de inatividade em segundos
var logoutUrl = '../../logout.php'; // URL para deslogar o usuário

// Obtém o caminho do URL atual
var currentPath = window.location.pathname;

// Verifica se a página atual é login.php ou cadastro.php
if (currentPath !== '/login.php' && currentPath !== '/cadastro.php' && currentPath !== '/alterarsenha.php' && currentPath !== '/recuperarsenha.php') {
    var timeout;

    function startTimer() {
        timeout = setTimeout(logoutUser, inactivityTime * 1000);
    }

    function resetTimer() {
        clearTimeout(timeout);
        startTimer();
    }

    function logoutUser() {
        // Envia uma requisição AJAX para o arquivo PHP para deslogar o usuário
        fetch(logoutUrl)
            .then(response => {
                if (response.ok) {
                    console.log('Usuário desconectado devido à inatividade.');
                    window.location.href = 'login.php'; // Redireciona para a página de login
                } else {
                    console.error('Erro ao tentar desconectar o usuário.');
                }
            })
            .catch(error => {
                console.error('Erro ao tentar desconectar o usuário:', error);
            });
    }

    // Inicia o timer de inatividade
    document.addEventListener('mousemove', resetTimer);
    document.addEventListener('mousedown', resetTimer);
    document.addEventListener('keypress', resetTimer);
    document.addEventListener('touchmove', resetTimer);
    document.addEventListener('scroll', resetTimer);

    startTimer(); // Inicia o timer quando a página é carregada
}
