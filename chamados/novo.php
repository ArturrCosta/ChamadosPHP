<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_login();

$errors = [];
$form = ['titulo' => '', 'categoria_id' => '', 'local' => '', 'descricao' => ''];
$categories = db()->query('SELECT id, nome, prioridade_padrao FROM categorias ORDER BY nome')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();
    $form = [
        'titulo' => trim((string) ($_POST['titulo'] ?? '')),
        'categoria_id' => (string) ($_POST['categoria_id'] ?? ''),
        'local' => trim((string) ($_POST['local'] ?? '')),
        'descricao' => trim((string) ($_POST['descricao'] ?? '')),
    ];
    $categoryId = filter_var($form['categoria_id'], FILTER_VALIDATE_INT);

    if ($form['titulo'] === '' || mb_strlen($form['titulo']) > 150) {
        $errors[] = 'Informe um título com até 150 caracteres.';
    }
    if ($form['local'] === '' || mb_strlen($form['local']) > 150) {
        $errors[] = 'Informe o local com até 150 caracteres.';
    }
    if ($form['descricao'] === '') {
        $errors[] = 'Descreva o problema.';
    }

    $categoryStmt = db()->prepare('SELECT id, prioridade_padrao FROM categorias WHERE id = :id');
    $categoryStmt->execute(['id' => $categoryId ?: 0]);
    $category = $categoryStmt->fetch();
    if (!$category) {
        $errors[] = 'Selecione uma categoria válida.';
    }

    if (!$errors) {
        $stmt = db()->prepare(
            "INSERT INTO chamados (usuario_id, categoria_id, titulo, descricao, local, prioridade, status)
             VALUES (:usuario_id, :categoria_id, :titulo, :descricao, :local, :prioridade, 'aberto')"
        );
        $stmt->execute([
            'usuario_id' => current_user()['id'],
            'categoria_id' => $category['id'],
            'titulo' => $form['titulo'],
            'descricao' => $form['descricao'],
            'local' => $form['local'],
            'prioridade' => $category['prioridade_padrao'],
        ]);
        set_flash('success', 'Chamado criado com prioridade ' . priority_label($category['prioridade_padrao']) . '.');
        redirect('chamados/');
    }
}

$pageTitle = 'Novo chamado';
require __DIR__ . '/../includes/header.php';
?>
<h1>Novo chamado</h1>
<p>Descreva o problema que está acontecendo.</p>
<?php if ($errors): ?><div class="message message-error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<form class="form-card" method="post">
    <?= csrf_field() ?>
    <div class="form-group"><label for="titulo">Título</label><input type="text" id="titulo" name="titulo" maxlength="150" value="<?= h($form['titulo']) ?>" placeholder="Ex.: Computador não liga" required></div>
    <div class="form-group"><label for="categoria_id">Tipo de problema</label><select id="categoria_id" name="categoria_id" required><option value="">Selecione</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= (string) $category['id'] === $form['categoria_id'] ? 'selected' : '' ?>><?= h($category['nome']) ?></option><?php endforeach; ?></select></div>
    <div class="form-group"><label for="local">Local</label><input type="text" id="local" name="local" maxlength="150" value="<?= h($form['local']) ?>" placeholder="Ex.: Sala 204" required></div>
    <div class="form-group"><label for="descricao">Descrição</label><textarea id="descricao" name="descricao" rows="6" placeholder="Explique o problema..." required><?= h($form['descricao']) ?></textarea></div>
    <div class="info-box">A prioridade será definida automaticamente conforme a categoria.</div>
    <button type="submit" class="btn">Enviar chamado</button>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
