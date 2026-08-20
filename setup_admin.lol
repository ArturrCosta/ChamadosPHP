<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/database.php';

$errors = [];
$success = '';
$databaseError = '';
$hasAdmin = false;
$form = ['nome' => '', 'documento' => '', 'email' => '', 'cargo' => 'Técnico de TI'];

try {
    $hasAdmin = (int) db()->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'ti' AND ativo = 1")->fetchColumn() > 0;
} catch (RuntimeException $exception) {
    $databaseError = $exception->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$hasAdmin && $databaseError === '') {
    validate_csrf();
    foreach (array_keys($form) as $field) {
        $form[$field] = trim((string) ($_POST[$field] ?? ''));
    }
    $password = (string) ($_POST['senha'] ?? '');
    $confirmation = (string) ($_POST['confirmacao'] ?? '');

    if ($form['nome'] === '' || mb_strlen($form['nome']) > 120) $errors[] = 'Informe um nome válido.';
    if ($form['documento'] === '' || mb_strlen($form['documento']) > 50) $errors[] = 'Informe um documento válido.';
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Informe um e-mail válido.';
    if ($form['cargo'] === '' || mb_strlen($form['cargo']) > 100) $errors[] = 'Informe um cargo válido.';
    if (mb_strlen($password) < 8) $errors[] = 'A senha deve ter pelo menos 8 caracteres.';
    if ($password !== $confirmation) $errors[] = 'A confirmação da senha não corresponde.';

    $duplicate = db()->prepare('SELECT id FROM usuarios WHERE documento = :documento');
    $duplicate->execute(['documento' => $form['documento']]);
    if ($duplicate->fetch()) $errors[] = 'Esse documento já está cadastrado.';

    if (!$errors) {
        $stmt = db()->prepare(
            "INSERT INTO usuarios (nome, documento, email, cargo, senha, tipo)
             VALUES (:nome, :documento, :email, :cargo, :senha, 'ti')"
        );
        $stmt->execute([
            'nome' => $form['nome'], 'documento' => $form['documento'], 'email' => $form['email'],
            'cargo' => $form['cargo'], 'senha' => password_hash($password, PASSWORD_DEFAULT),
        ]);
        $success = 'Primeiro usuário de TI criado. Por segurança, remova ou renomeie setup_admin.php.';
        $hasAdmin = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuração inicial - Setor de TI</title>
    <link rel="stylesheet" href="<?= h(app_url('assets/css/style.css')) ?>">
</head>
<body>
<main class="login-container"><section class="login-card setup-card">
    <div class="login-header"><h1>Configuração inicial</h1><p>Criação segura do primeiro usuário de TI</p></div>
    <?php if ($databaseError): ?><div class="message message-error"><?= h($databaseError) ?><br>Importe <strong>database.sql</strong> antes de continuar.</div>
    <?php elseif ($success): ?><div class="message message-success"><?= h($success) ?></div><a class="btn btn-block" href="<?= h(app_url('index.php')) ?>">Ir para o login</a>
    <?php elseif ($hasAdmin): ?><div class="message message-error">Já existe um usuário de TI ativo. Este instalador está bloqueado.</div><a class="btn btn-block" href="<?= h(app_url('index.php')) ?>">Ir para o login</a>
    <?php else: ?>
        <?php if ($errors): ?><div class="message message-error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post"><?= csrf_field() ?>
            <div class="form-group"><label for="nome">Nome completo</label><input id="nome" name="nome" maxlength="120" value="<?= h($form['nome']) ?>" required></div>
            <div class="form-group"><label for="documento">Documento / Matrícula</label><input id="documento" name="documento" maxlength="50" value="<?= h($form['documento']) ?>" required></div>
            <div class="form-group"><label for="email">E-mail</label><input id="email" name="email" type="email" maxlength="150" value="<?= h($form['email']) ?>" required></div>
            <div class="form-group"><label for="cargo">Cargo</label><input id="cargo" name="cargo" maxlength="100" value="<?= h($form['cargo']) ?>" required></div>
            <div class="form-group"><label for="senha">Senha</label><input id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" required></div>
            <div class="form-group"><label for="confirmacao">Confirmar senha</label><input id="confirmacao" name="confirmacao" type="password" minlength="8" autocomplete="new-password" required></div>
            <button class="btn-login" type="submit">Criar usuário de TI</button>
        </form>
    <?php endif; ?>
</section></main>
</body></html>
