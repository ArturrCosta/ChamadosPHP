<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_ti();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { set_flash('error', 'Usuário inválido.'); redirect('usuarios/'); }
$stmt = db()->prepare('SELECT id, nome, documento FROM usuarios WHERE id = :id');
$stmt->execute(['id' => $id]);
$editedUser = $stmt->fetch();
if (!$editedUser) { set_flash('error', 'Usuário não encontrado.'); redirect('usuarios/'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();
    $password = (string) ($_POST['senha'] ?? '');
    $confirmation = (string) ($_POST['confirmacao'] ?? '');
    if (mb_strlen($password) < 8) $errors[] = 'A nova senha deve ter pelo menos 8 caracteres.';
    if ($password !== $confirmation) $errors[] = 'A confirmação de senha não corresponde.';

    if (!$errors) {
        $update = db()->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
        $update->execute(['senha' => password_hash($password, PASSWORD_DEFAULT), 'id' => $id]);
        set_flash('success', 'Senha de ' . $editedUser['nome'] . ' redefinida com sucesso.');
        redirect('usuarios/');
    }
}

$pageTitle = 'Redefinir senha';
require __DIR__ . '/../includes/header.php';
?>
<h1>Redefinir senha</h1><p>Defina uma nova senha para o usuário.</p>
<?php if ($errors): ?><div class="message message-error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="form-card" method="post">
    <?= csrf_field() ?>
    <div class="form-group"><label>Usuário</label><input type="text" value="<?= h($editedUser['nome'] . ' (' . $editedUser['documento'] . ')') ?>" disabled></div>
    <div class="form-group"><label for="senha">Nova senha</label><input id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" required></div>
    <div class="form-group"><label for="confirmacao">Confirmar nova senha</label><input id="confirmacao" name="confirmacao" type="password" minlength="8" autocomplete="new-password" required></div>
    <div class="button-group"><button class="btn" type="submit">Redefinir senha</button><a class="btn btn-secondary" href="./">Cancelar</a></div>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
