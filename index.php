<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/database.php';

if (is_logged_in()) {
    redirect('dashboard/');
}

$error = '';
$flash = get_flash();
$documento = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();
    $documento = trim((string) ($_POST['documento'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    if ($documento === '' || $senha === '') {
        $error = 'Preencha o documento e a senha.';
    } else {
        try {
            $stmt = db()->prepare(
                'SELECT id, nome, senha, tipo FROM usuarios WHERE documento = :documento AND ativo = 1 LIMIT 1'
            );
            $stmt->execute(['documento' => $documento]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = [
                    'id' => (int) $usuario['id'],
                    'nome' => $usuario['nome'],
                    'tipo' => $usuario['tipo'],
                ];
                unset($_SESSION['csrf_token']);
                redirect('dashboard/');
            }

            $error = 'Documento ou senha incorretos, ou usuário inativo.';
        } catch (RuntimeException $exception) {
            $error = $exception->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor de TI da Firma</title>
    <link rel="stylesheet" href="<?= h(app_url('assets/css/style.css')) ?>">
</head>
<body>
<main class="login-container">
    <section class="login-card">
        <div class="login-header">
            <h1>Setor de TI da Firma</h1>
            <p>Acesse sua conta para continuar</p>
        </div>

        <?php if ($flash): ?>
            <div class="message <?= $flash['type'] === 'success' ? 'message-success' : 'message-error' ?>">
                <?= h($flash['message']) ?>
            </div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <div id="login-error" class="message message-error" role="alert"><?= h($error) ?></div>
        <?php endif; ?>

        <form id="login-form" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="documento">Documento / Matrícula</label>
                <input type="text" id="documento" name="documento" value="<?= h($documento) ?>"
                       placeholder="Digite seu documento ou matrícula" autocomplete="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha"
                       autocomplete="current-password" required>
            </div>
            <p id="client-error" class="error-message"></p>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </section>
</main>
<script src="<?= h(app_url('assets/js/script.js')) ?>"></script>
</body>
</html>
