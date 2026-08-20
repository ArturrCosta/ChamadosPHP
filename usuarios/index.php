<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_ti();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $action = (string) ($_POST['acao'] ?? '');

    if (!$id || !in_array($action, ['ativar', 'desativar'], true)) {
        set_flash('error', 'Operação inválida.');
    } elseif ($action === 'desativar' && (int) $id === (int) current_user()['id']) {
        set_flash('error', 'Você não pode desativar o próprio usuário.');
    } else {
        $active = $action === 'ativar' ? 1 : 0;
        $stmt = db()->prepare('UPDATE usuarios SET ativo = :ativo WHERE id = :id');
        $stmt->execute(['ativo' => $active, 'id' => $id]);
        set_flash('success', $active ? 'Usuário reativado.' : 'Usuário desativado sem apagar o histórico.');
    }
    redirect('usuarios/');
}

$users = db()->query('SELECT id, nome, documento, email, cargo, tipo, ativo, created_at FROM usuarios ORDER BY nome')->fetchAll();
$pageTitle = 'Usuários';
require __DIR__ . '/../includes/header.php';
?>
<div class="section-header">
    <div><h1>Usuários</h1><p>Gerenciamento dos usuários da empresa.</p></div>
    <a href="novo.php" class="btn">+ Novo usuário</a>
</div>
<div class="table-wrapper">
<table>
    <thead><tr><th>Nome</th><th>Documento</th><th>E-mail</th><th>Cargo</th><th>Tipo</th><th>Situação</th><th>Ações</th></tr></thead>
    <tbody>
    <?php if (!$users): ?><tr><td colspan="7" class="empty-state">Nenhum usuário cadastrado.</td></tr><?php endif; ?>
    <?php foreach ($users as $listedUser): ?>
        <tr class="<?= $listedUser['ativo'] ? '' : 'inactive-row' ?>">
            <td><?= h($listedUser['nome']) ?></td><td><?= h($listedUser['documento']) ?></td><td><?= h($listedUser['email']) ?></td><td><?= h($listedUser['cargo']) ?></td><td><?= h(user_type_label($listedUser['tipo'])) ?></td>
            <td><span class="badge <?= $listedUser['ativo'] ? 'status-resolvido' : 'status-cancelado' ?>"><?= $listedUser['ativo'] ? 'Ativo' : 'Inativo' ?></span></td>
            <td class="actions">
                <a href="editar.php?id=<?= (int) $listedUser['id'] ?>">Editar</a>
                <a href="trocar_senha.php?id=<?= (int) $listedUser['id'] ?>">Senha</a>
                <?php if ((int) $listedUser['id'] !== (int) current_user()['id']): ?>
                    <form method="post" class="inline-form" data-confirm="<?= $listedUser['ativo'] ? 'Deseja desativar este usuário?' : 'Deseja reativar este usuário?' ?>">
                        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $listedUser['id'] ?>">
                        <button type="submit" class="link-button" name="acao" value="<?= $listedUser['ativo'] ? 'desativar' : 'ativar' ?>"><?= $listedUser['ativo'] ? 'Desativar' : 'Reativar' ?></button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
