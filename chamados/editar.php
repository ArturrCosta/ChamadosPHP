<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    set_flash('error', 'Chamado inválido.');
    redirect('chamados/');
}

$allowedStatuses = ['aberto', 'em_andamento', 'resolvido', 'cancelado'];
$allowedPriorities = ['baixa', 'media', 'alta', 'urgente'];
$errors = [];

function load_call(int $id): array|false
{
    $conditions = ['c.id = :id'];
    $params = ['id' => $id];
    if (!is_ti()) {
        $conditions[] = 'c.usuario_id = :usuario_id';
        $params['usuario_id'] = current_user()['id'];
    }
    $stmt = db()->prepare(
        'SELECT c.*, u.nome AS usuario_nome, u.email AS usuario_email, cat.nome AS categoria_nome,
                r.nome AS responsavel_nome
         FROM chamados c
         INNER JOIN usuarios u ON u.id = c.usuario_id
         INNER JOIN categorias cat ON cat.id = c.categoria_id
         LEFT JOIN usuarios r ON r.id = c.responsavel_id
         WHERE ' . implode(' AND ', $conditions)
    );
    $stmt->execute($params);
    return $stmt->fetch();
}

$call = load_call((int) $id);
if (!$call) {
    http_response_code(404);
    set_flash('error', 'Chamado não encontrado ou acesso não permitido.');
    redirect('chamados/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_ti();
    validate_csrf();
    $action = (string) ($_POST['acao'] ?? 'salvar');

    if ($action === 'assumir') {
        $stmt = db()->prepare(
            "UPDATE chamados SET responsavel_id = :responsavel_id,
             status = IF(status = 'aberto', 'em_andamento', status) WHERE id = :id"
        );
        $stmt->execute(['responsavel_id' => current_user()['id'], 'id' => $id]);
        set_flash('success', 'Chamado atribuído a você.');
        redirect('chamados/editar.php?id=' . $id);
    }

    $status = (string) ($_POST['status'] ?? '');
    $priority = (string) ($_POST['prioridade'] ?? '');
    $observation = trim((string) ($_POST['observacao_ti'] ?? ''));
    if (!in_array($status, $allowedStatuses, true)) {
        $errors[] = 'Status inválido.';
    }
    if (!in_array($priority, $allowedPriorities, true)) {
        $errors[] = 'Prioridade inválida.';
    }
    if (mb_strlen($observation) > 5000) {
        $errors[] = 'A observação deve ter até 5.000 caracteres.';
    }

    if (!$errors) {
        $stmt = db()->prepare(
            "UPDATE chamados SET status = :status, prioridade = :prioridade, observacao_ti = :observacao,
             data_fechamento = CASE WHEN :status_fechamento = 'resolvido' THEN COALESCE(data_fechamento, NOW()) ELSE NULL END
             WHERE id = :id"
        );
        $stmt->execute([
            'status' => $status,
            'prioridade' => $priority,
            'observacao' => $observation !== '' ? $observation : null,
            'status_fechamento' => $status,
            'id' => $id,
        ]);
        set_flash('success', 'Chamado atualizado com sucesso.');
        redirect('chamados/editar.php?id=' . $id);
    }
}

$call = load_call((int) $id);
$pageTitle = 'Chamado #' . $id;
require __DIR__ . '/../includes/header.php';
?>
<div class="section-header"><div><h1>Chamado #<?= str_pad((string) $id, 4, '0', STR_PAD_LEFT) ?></h1><p>Detalhes e atendimento do chamado.</p></div><a href="./" class="btn btn-secondary">Voltar</a></div>
<?php if ($errors): ?><div class="message message-error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="form-card details-grid">
    <div class="detail"><strong>Funcionário</strong><span><?= h($call['usuario_nome']) ?></span></div>
    <div class="detail"><strong>E-mail</strong><span><?= h($call['usuario_email']) ?></span></div>
    <div class="detail"><strong>Título</strong><span><?= h($call['titulo']) ?></span></div>
    <div class="detail"><strong>Categoria</strong><span><?= h($call['categoria_nome']) ?></span></div>
    <div class="detail"><strong>Local</strong><span><?= h($call['local']) ?></span></div>
    <div class="detail"><strong>Responsável</strong><span><?= h($call['responsavel_nome'] ?: 'Não atribuído') ?></span></div>
    <div class="detail detail-full"><strong>Descrição</strong><span class="pre-line"><?= h($call['descricao']) ?></span></div>
    <div class="detail"><strong>Abertura</strong><span><?= h(date('d/m/Y H:i', strtotime($call['data_abertura']))) ?></span></div>
    <div class="detail"><strong>Fechamento</strong><span><?= $call['data_fechamento'] ? h(date('d/m/Y H:i', strtotime($call['data_fechamento']))) : '—' ?></span></div>
</div>

<?php if (is_ti()): ?>
    <form class="form-card" method="post">
        <?= csrf_field() ?>
        <div class="form-row">
            <div class="form-group"><label for="prioridade">Prioridade</label><select id="prioridade" name="prioridade" required><?php foreach ($allowedPriorities as $value): ?><option value="<?= h($value) ?>" <?= $call['prioridade'] === $value ? 'selected' : '' ?>><?= h(priority_label($value)) ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label for="status">Status</label><select id="status" name="status" required><?php foreach ($allowedStatuses as $value): ?><option value="<?= h($value) ?>" <?= $call['status'] === $value ? 'selected' : '' ?>><?= h(status_label($value)) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="form-group"><label for="observacao_ti">Observação da TI</label><textarea id="observacao_ti" name="observacao_ti" rows="4" maxlength="5000" placeholder="Registre informações sobre o atendimento"><?= h($call['observacao_ti'] ?? '') ?></textarea></div>
        <div class="button-group">
            <button type="submit" name="acao" value="salvar" class="btn">Salvar alterações</button>
            <?php if ((int) ($call['responsavel_id'] ?? 0) !== (int) current_user()['id']): ?><button type="submit" name="acao" value="assumir" class="btn btn-secondary">Assumir chamado</button><?php endif; ?>
        </div>
    </form>
<?php elseif ($call['observacao_ti']): ?>
    <div class="form-card"><h2>Observação da TI</h2><p class="pre-line"><?= h($call['observacao_ti']) ?></p></div>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
