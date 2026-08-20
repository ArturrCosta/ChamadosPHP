<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_ti();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { set_flash('error', 'Usuário inválido.'); redirect('usuarios/'); }
$stmt = db()->prepare('SELECT id, nome, documento, email, cargo, tipo FROM usuarios WHERE id = :id');
$stmt->execute(['id' => $id]);
$editedUser = $stmt->fetch();
if (!$editedUser) { set_flash('error', 'Usuário não encontrado.'); redirect('usuarios/'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();
    $editedUser['nome'] = trim((string) ($_POST['nome'] ?? ''));
    $editedUser['email'] = trim((string) ($_POST['email'] ?? ''));
    $editedUser['cargo'] = trim((string) ($_POST['cargo'] ?? ''));
    $editedUser['tipo'] = trim((string) ($_POST['tipo'] ?? ''));

    if ($editedUser['nome'] === '' || mb_strlen($editedUser['nome']) > 120) $errors[] = 'Informe um nome com até 120 caracteres.';
    if (!filter_var($editedUser['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($editedUser['email']) > 150) $errors[] = 'Informe um e-mail válido.';
    if ($editedUser['cargo'] === '' || mb_strlen($editedUser['cargo']) > 100) $errors[] = 'Informe um cargo com até 100 caracteres.';
    if (!in_array($editedUser['tipo'], ['funcionario', 'ti'], true)) $errors[] = 'Tipo de usuário inválido.';
    if ((int) $id === (int) current_user()['id'] && $editedUser['tipo'] !== 'ti') $errors[] = 'Você não pode remover sua própria permissão de TI.';

    if (!$errors) {
        $update = db()->prepare('UPDATE usuarios SET nome = :nome, email = :email, cargo = :cargo, tipo = :tipo WHERE id = :id');
        $update->execute(['nome' => $editedUser['nome'], 'email' => $editedUser['email'], 'cargo' => $editedUser['cargo'], 'tipo' => $editedUser['tipo'], 'id' => $id]);
        if ((int) $id === (int) current_user()['id']) $_SESSION['usuario']['nome'] = $editedUser['nome'];
        set_flash('success', 'Usuário atualizado com sucesso.');
        redirect('usuarios/');
    }
}

$pageTitle = 'Editar usuário';
require __DIR__ . '/../includes/header.php';
?>
<h1>Editar usuário</h1>
<?php if ($errors): ?><div class="message message-error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="form-card" method="post">
    <?= csrf_field() ?>
    <div class="form-group"><label for="nome">Nome completo</label><input id="nome" name="nome" type="text" maxlength="120" value="<?= h($editedUser['nome']) ?>" required></div>
    <div class="form-group"><label>Documento / Matrícula</label><input type="text" value="<?= h($editedUser['documento']) ?>" disabled><small>O documento não é alterado nesta tela.</small></div>
    <div class="form-group"><label for="email">E-mail</label><input id="email" name="email" type="email" maxlength="150" value="<?= h($editedUser['email']) ?>" required></div>
    <div class="form-group"><label for="cargo">Cargo</label><input id="cargo" name="cargo" type="text" maxlength="100" value="<?= h($editedUser['cargo']) ?>" required></div>
    <div class="form-group"><label for="tipo">Tipo de usuário</label><select id="tipo" name="tipo" required><option value="funcionario" <?= $editedUser['tipo'] === 'funcionario' ? 'selected' : '' ?>>Funcionário</option><option value="ti" <?= $editedUser['tipo'] === 'ti' ? 'selected' : '' ?>>TI</option></select></div>
    <div class="button-group"><button class="btn" type="submit">Salvar alterações</button><a class="btn btn-secondary" href="./">Cancelar</a></div>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
