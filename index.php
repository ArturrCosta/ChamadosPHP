<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Setor de TI da Firma</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <main class="login-container">

        <section class="login-card">

            <div class="login-header">
                <h1>Setor de TI da Empresa</h1>
                <p>Acesse sua conta para continuar</p>
            </div>

            <form id="login-form" action="#" method="POST">

                <div class="form-group">
                    <label for="documento">Documento / Matrícula</label>
                    <input
                        type="text"
                        id="documento"
                        name="documento"
                        placeholder="Digite seu documento ou matrícula"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Digite sua senha"
                        required
                    >
                </div>

                <p id="login-error" class="error-message">
                    Documento ou senha incorretos.
                </p>

                <button type="submit" class="btn-login">
                    Entrar
                </button>

            </form>

        </section>

    </main>

    <script src="assets/js/script.js"></script>

</body>
</html>