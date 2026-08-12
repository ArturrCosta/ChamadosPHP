const loginForm = document.getElementById("login-form");

if (loginForm) {

    loginForm.addEventListener("submit", function (event) {

        const documento = document.getElementById("documento").value.trim();
        const senha = document.getElementById("senha").value.trim();
        const erro = document.getElementById("login-error");

        if (documento === "" || senha === "") {

            event.preventDefault();

            erro.style.display = "block";
            erro.textContent = "Preencha todos os campos.";

        }

    });

}