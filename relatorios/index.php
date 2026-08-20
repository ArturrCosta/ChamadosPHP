<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_ti();

$allowedStatuses = ['aberto', 'em_andamento', 'resolvido', 'cancelado'];
$allowedPriorities = ['baixa', 'media', 'alta', 'urgente'];
$filters = [
    'data_inicial' => trim((string) ($_GET['data_inicial'] ?? '')),
    'data_final' => trim((string) ($_GET['data_final'] ?? '')),
    'status' => (string) ($_GET['status'] ?? ''),
    'prioridade' => (string) ($_GET['prioridade'] ?? ''),
    'categoria' => filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: 0,
];
$errors = [];
if ($filters['data_inicial'] !== '' && !valid_date($filters['data_inicial'])) { $errors[] = 'Data inicial inválida.'; $filters['data_inicial'] = ''; }
if ($filters['data_final'] !== '' && !valid_date($filters['data_final'])) { $errors[] = 'Data final inválida.'; $filters['data_final'] = ''; }
if ($filters['data_inicial'] && $filters['data_final'] && $filters['data_inicial'] > $filters['data_final']) $errors[] = 'A data inicial não pode ser posterior à data final.';
if (!in_array($filters['status'], $allowedStatuses, true)) $filters['status'] = '';
if (!in_array($filters['prioridade'], $allowedPriorities, true)) $filters['prioridade'] = '';

$conditions = [];
$params = [];
if ($filters['data_inicial']) { $conditions[] = 'c.data_abertura >= :data_inicial'; $params['data_inicial'] = $filters['data_inicial'] . ' 00:00:00'; }
if ($filters['data_final']) { $conditions[] = 'c.data_abertura <= :data_final'; $params['data_final'] = $filters['data_final'] . ' 23:59:59'; }
if ($filters['status']) { $conditions[] = 'c.status = :status'; $params['status'] = $filters['status']; }
if ($filters['prioridade']) { $conditions[] = 'c.prioridade = :prioridade'; $params['prioridade'] = $filters['prioridade']; }
if ($filters['categoria']) { $conditions[] = 'c.categoria_id = :categoria_id'; $params['categoria_id'] = $filters['categoria']; }
$where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';

$stmt = db()->prepare(
    "SELECT c.id, c.titulo, c.local, c.prioridade, c.status, c.data_abertura, c.data_fechamento,
            u.nome AS usuario_nome, cat.nome AS categoria_nome, r.nome AS responsavel_nome
     FROM chamados c
     INNER JOIN usuarios u ON u.id = c.usuario_id
     INNER JOIN categorias cat ON cat.id = c.categoria_id
     LEFT JOIN usuarios r ON r.id = c.responsavel_id{$where}
     ORDER BY c.data_abertura DESC"
);
$stmt->execute($params);
$calls = $stmt->fetchAll();

if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv' && !$errors) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="relatorio_chamados_' . date('Y-m-d_H-i') . '.csv"');
    echo "\xEF\xBB\xBF";
    $output = fopen('php://output', 'wb');
    fputcsv($output, ['ID', 'Funcionário', 'Título', 'Categoria', 'Local', 'Prioridade', 'Status', 'Abertura', 'Fechamento', 'Responsável'], ';');
    foreach ($calls as $call) {
        fputcsv($output, [
            $call['id'], $call['usuario_nome'], $call['titulo'], $call['categoria_nome'], $call['local'],
            priority_label($call['prioridade']), status_label($call['status']), $call['data_abertura'],
            $call['data_fechamento'] ?: '', $call['responsavel_nome'] ?: 'Não atribuído',
        ], ';');
    }
    fclose($output);
    exit;
}

$categories = db()->query('SELECT id, nome FROM categorias ORDER BY nome')->fetchAll();
$exportParams = $_GET;
$exportParams['exportar'] = 'csv';
$pageTitle = 'Relatórios';
require __DIR__ . '/../includes/header.php';
?>
<div class="section-header"><div><h1>Relatórios</h1><p>Consulte e exporte informações sobre os chamados.</p></div><?php if (!$errors): ?><a class="btn" href="?<?= h(http_build_query($exportParams)) ?>">Exportar CSV</a><?php endif; ?></div>
<?php if ($errors): ?><div class="message message-error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="filters report-filters" method="get">
    <div class="filter-field"><label for="data_inicial">Data inicial</label><input id="data_inicial" type="date" name="data_inicial" value="<?= h($filters['data_inicial']) ?>"></div>
    <div class="filter-field"><label for="data_final">Data final</label><input id="data_final" type="date" name="data_final" value="<?= h($filters['data_final']) ?>"></div>
    <div class="filter-field"><label for="status">Status</label><select id="status" name="status"><option value="">Todos</option><?php foreach ($allowedStatuses as $value): ?><option value="<?= h($value) ?>" <?= $filters['status'] === $value ? 'selected' : '' ?>><?= h(status_label($value)) ?></option><?php endforeach; ?></select></div>
    <div class="filter-field"><label for="prioridade">Prioridade</label><select id="prioridade" name="prioridade"><option value="">Todas</option><?php foreach ($allowedPriorities as $value): ?><option value="<?= h($value) ?>" <?= $filters['prioridade'] === $value ? 'selected' : '' ?>><?= h(priority_label($value)) ?></option><?php endforeach; ?></select></div>
    <div class="filter-field"><label for="categoria">Categoria</label><select id="categoria" name="categoria"><option value="">Todas</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= (int) $filters['categoria'] === (int) $category['id'] ? 'selected' : '' ?>><?= h($category['nome']) ?></option><?php endforeach; ?></select></div>
    <div class="filter-actions"><button class="btn" type="submit">Gerar relatório</button><a class="btn btn-secondary" href="./">Limpar</a></div>
</form>

<p class="result-count"><?= count($calls) ?> chamado(s) encontrado(s).</p>
<div class="table-wrapper"><table>
    <thead><tr><th>ID</th><th>Funcionário</th><th>Título</th><th>Categoria</th><th>Prioridade</th><th>Status</th><th>Abertura</th><th>Fechamento</th><th>Responsável</th></tr></thead>
    <tbody>
    <?php if (!$calls): ?><tr><td colspan="9" class="empty-state">Nenhum chamado encontrado.</td></tr><?php endif; ?>
    <?php foreach ($calls as $call): ?><tr>
        <td>#<?= (int) $call['id'] ?></td><td><?= h($call['usuario_nome']) ?></td><td><?= h($call['titulo']) ?></td><td><?= h($call['categoria_nome']) ?></td>
        <td><?= h(priority_label($call['prioridade'])) ?></td><td><?= h(status_label($call['status'])) ?></td><td><?= h(date('d/m/Y H:i', strtotime($call['data_abertura']))) ?></td>
        <td><?= $call['data_fechamento'] ? h(date('d/m/Y H:i', strtotime($call['data_fechamento']))) : '—' ?></td><td><?= h($call['responsavel_nome'] ?: 'Não atribuído') ?></td>
    </tr><?php endforeach; ?>
    </tbody>
</table></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
