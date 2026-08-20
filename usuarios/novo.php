<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_ti();

$errors = [];
$form = ['nome' => '', 'documento' => '', 'email' => '', 'cargo' => '', 'tipo' => 'funcionario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();
    foreach (array_keys($form) as $field) {
        $form[$field] = trim((string) ($_POST[$field] ?? ''));
    }
    $password = (string) ($_POST['senha'] ?? '');

    if ($form['nome'] === '' || mb_strlen($form['nome']) > 120) $errors[] = 'Informe um nome com até 120 caracteres.';
    if ($form['documento'] === '' || mb_strlen($form['documento']) > 50) $errors[] = 'Informe um documento com até 50 caracteres.';
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($form['email']) > 150) $errors[] = 'Informe um e-mail válido.';
    if ($form['cargo'] === '' || mb_strlen($form['cargo']) > 100) $errors[] = 'Informe um cargo com até 100 caracteres.';
    if (!in_array($form['tipo'], ['funcionario', 'ti'], true)) $errors[] = 'Tipo de usuário inválido.';
    if (mb_strlen($password) < 8) $errors[] = 'A senha inicial deve ter pelo menos 8 caracteres.';

    $duplicateStmt = db()->prepare('SELECT id FROM usuarios WHERE documento = :documento');
    $duplicateStmt->execute(['documento' => $form['documento']]);
    if ($duplicateStmt->fetch()) $errors[] = 'Já existe um usuário com esse documento.';

    if (!$errors) {
        $stmt = db()->prepare(
            'INSERT INTO usuarios (nome, documento, email, cargo, senha, tipo)
             VALUES (:nome, :documento, :email, :cargo, :senha, :tipo)'
        );
        $stmt->execute([
            'nome' => $form['nome'], 'documento' => $form['documento'], 'email' => $form['email'],
            'cargo' => $form['cargo'], 'senha' => password_hash($password, PASSWORD_DEFAULT), 'tipo' => $form['tipo'],
        ]);
        set_flash('success', 'Usuário cadastrado com sucesso.');
        redirect('usuarios/');
    }
}

$pageTitle = 'Novo usuário';
require __DIR__ . '/../includes/header.php';
?>
<h1>Novo usuário</h1><p>Cadastre um novo usuário no sistema.</p>
<?php if ($errors): ?><div class="message message-error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="form-card" method="post">
    <?= csrf_field() ?>
    <div class="form-group"><label for="nome">Nome completo</label><input id="nome" name="nome" type="text" maxlength="120" value="<?= h($form['nome']) ?>" required></div>
    <div class="form-group"><label for="documento">Documento / Matrícula</label><input id="documento" name="documento" type="text" maxlength="50" value="<?= h($form['documento']) ?>" required></div>
    <div class="form-group"><label for="email">E-mail</label><input id="email" name="email" type="email" maxlength="150" value="<?= h($form['email']) ?>" required></div>
    <div class="form-group"><label for="cargo">Cargo</label><input id="cargo" name="cargo" type="text" maxlength="100" value="<?= h($form['cargo']) ?>" required></div>
    <div class="form-group"><label for="tipo">Tipo de usuário</label><select id="tipo" name="tipo" required><option value="funcionario" <?= $form['tipo'] === 'funcionario' ? 'selected' : '' ?>>Funcionário</option><option value="ti" <?= $form['tipo'] === 'ti' ? 'selected' : '' ?>>TI</option></select></div>
    <div class="form-group"><label for="senha">Senha inicial</label><input id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" required><small>Mínimo de 8 caracteres.</small></div>
    <button class="btn" type="submit">Cadastrar usuário</button>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
