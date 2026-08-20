const loginForm = document.getElementById('login-form');

if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
        const documento = document.getElementById('documento');
        const senha = document.getElementById('senha');
        const erro = document.getElementById('client-error');

        if (documento && senha && (documento.value.trim() === '' || senha.value === '')) {
            event.preventDefault();
            erro.style.display = 'block';
            erro.textContent = 'Preencha todos os campos.';
        }
    });
}

document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
