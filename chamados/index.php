<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_login();

$allowedStatuses = ['aberto', 'em_andamento', 'resolvido', 'cancelado'];
$allowedPriorities = ['baixa', 'media', 'alta', 'urgente'];
$status = (string) ($_GET['status'] ?? '');
$priority = (string) ($_GET['prioridade'] ?? '');
$categoryId = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: 0;
$search = trim((string) ($_GET['busca'] ?? ''));

if (!in_array($status, $allowedStatuses, true)) {
    $status = '';
}
if (!in_array($priority, $allowedPriorities, true)) {
    $priority = '';
}

$conditions = [];
$params = [];
if (!is_ti()) {
    $conditions[] = 'c.usuario_id = :usuario_id';
    $params['usuario_id'] = current_user()['id'];
}
if ($status !== '') {
    $conditions[] = 'c.status = :status';
    $params['status'] = $status;
}
if ($priority !== '') {
    $conditions[] = 'c.prioridade = :prioridade';
    $params['prioridade'] = $priority;
}
if ($categoryId > 0) {
    $conditions[] = 'c.categoria_id = :categoria_id';
    $params['categoria_id'] = $categoryId;
}
if ($search !== '') {
    $conditions[] = '(c.titulo LIKE :busca_titulo OR c.descricao LIKE :busca_descricao OR c.local LIKE :busca_local)';
    $searchValue = '%' . $search . '%';
    $params['busca_titulo'] = $searchValue;
    $params['busca_descricao'] = $searchValue;
    $params['busca_local'] = $searchValue;
}
$where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';

$stmt = db()->prepare(
    "SELECT c.id, c.titulo, c.prioridade, c.status, c.data_abertura,
            u.nome AS usuario_nome, cat.nome AS categoria_nome, r.nome AS responsavel_nome
     FROM chamados c
     INNER JOIN usuarios u ON u.id = c.usuario_id
     INNER JOIN categorias cat ON cat.id = c.categoria_id
     LEFT JOIN usuarios r ON r.id = c.responsavel_id{$where}
     ORDER BY c.data_abertura DESC"
);
$stmt->execute($params);
$calls = $stmt->fetchAll();
$categories = db()->query('SELECT id, nome FROM categorias ORDER BY nome')->fetchAll();

$pageTitle = 'Chamados';
require __DIR__ . '/../includes/header.php';
?>
<div class="section-header">
    <div><h1>Chamados</h1><p><?= is_ti() ? 'Gerenciamento de todos os chamados de suporte.' : 'Acompanhe seus chamados de suporte.' ?></p></div>
    <a href="novo.php" class="btn">+ Novo chamado</a>
</div>

<form class="filters" method="get">
    <input type="search" name="busca" value="<?= h($search) ?>" placeholder="Pesquisar título, descrição ou local">
    <select name="status">
        <option value="">Todos os status</option>
        <?php foreach ($allowedStatuses as $value): ?><option value="<?= h($value) ?>" <?= $status === $value ? 'selected' : '' ?>><?= h(status_label($value)) ?></option><?php endforeach; ?>
    </select>
    <select name="prioridade">
        <option value="">Todas as prioridades</option>
        <?php foreach ($allowedPriorities as $value): ?><option value="<?= h($value) ?>" <?= $priority === $value ? 'selected' : '' ?>><?= h(priority_label($value)) ?></option><?php endforeach; ?>
    </select>
    <select name="categoria">
        <option value="">Todas as categorias</option>
        <?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>><?= h($category['nome']) ?></option><?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Filtrar</button>
    <a class="btn btn-secondary" href="./">Limpar</a>
</form>

<div class="table-wrapper">
<table>
    <thead><tr><th>ID</th><th>Funcionário</th><th>Problema</th><th>Categoria</th><th>Prioridade</th><th>Status</th><th>Data</th><th>Responsável</th><th>Ação</th></tr></thead>
    <tbody>
    <?php if (!$calls): ?><tr><td colspan="9" class="empty-state">Nenhum chamado encontrado.</td></tr><?php endif; ?>
    <?php foreach ($calls as $call): ?>
        <tr>
            <td>#<?= str_pad((string) $call['id'], 4, '0', STR_PAD_LEFT) ?></td>
            <td><?= h($call['usuario_nome']) ?></td>
            <td><?= h($call['titulo']) ?></td>
            <td><?= h($call['categoria_nome']) ?></td>
            <td><span class="badge priority-<?= h($call['prioridade']) ?>"><?= h(priority_label($call['prioridade'])) ?></span></td>
            <td><span class="badge status-<?= h($call['status']) ?>"><?= h(status_label($call['status'])) ?></span></td>
            <td><?= h(date('d/m/Y H:i', strtotime($call['data_abertura']))) ?></td>
            <td><?= h($call['responsavel_nome'] ?: 'Não atribuído') ?></td>
            <td><a href="editar.php?id=<?= (int) $call['id'] ?>"><?= is_ti() ? 'Atender' : 'Visualizar' ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
