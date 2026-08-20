<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_login();

$user = current_user();
$where = is_ti() ? '' : ' WHERE c.usuario_id = :usuario_id';
$params = is_ti() ? [] : ['usuario_id' => $user['id']];

$stmt = db()->prepare(
    "SELECT
        SUM(c.status = 'aberto') AS abertos,
        SUM(c.status = 'em_andamento') AS em_andamento,
        SUM(c.status = 'resolvido') AS resolvidos,
        SUM(c.prioridade = 'urgente' AND c.status NOT IN ('resolvido', 'cancelado')) AS urgentes
     FROM chamados c{$where}"
);
$stmt->execute($params);
$totals = $stmt->fetch() ?: [];

$latestStmt = db()->prepare(
    "SELECT c.id, c.titulo, c.prioridade, c.status, c.data_abertura, u.nome AS usuario_nome
     FROM chamados c
     INNER JOIN usuarios u ON u.id = c.usuario_id{$where}
     ORDER BY c.data_abertura DESC LIMIT 5"
);
$latestStmt->execute($params);
$latest = $latestStmt->fetchAll();

$pageTitle = 'Dashboard';
require __DIR__ . '/../includes/header.php';
?>
<header class="page-header">
    <h1>Dashboard</h1>
    <p>Bem-vindo, <?= h($user['nome']) ?>. <?= is_ti() ? 'Visão geral dos chamados.' : 'Acompanhe seus chamados.' ?></p>
</header>

<section class="cards">
    <div class="card"><span>Chamados abertos</span><strong><?= (int) ($totals['abertos'] ?? 0) ?></strong></div>
    <div class="card"><span>Em andamento</span><strong><?= (int) ($totals['em_andamento'] ?? 0) ?></strong></div>
    <div class="card"><span>Resolvidos</span><strong><?= (int) ($totals['resolvidos'] ?? 0) ?></strong></div>
    <div class="card"><span>Urgentes ativos</span><strong><?= (int) ($totals['urgentes'] ?? 0) ?></strong></div>
</section>

<section class="dashboard-section">
    <div class="section-header">
        <h2>Últimos chamados</h2>
        <a href="<?= h(app_url('chamados/novo.php')) ?>" class="btn">Novo chamado</a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>ID</th><?php if (is_ti()): ?><th>Funcionário</th><?php endif; ?><th>Problema</th><th>Prioridade</th><th>Status</th><th>Data</th></tr></thead>
            <tbody>
            <?php if (!$latest): ?>
                <tr><td colspan="<?= is_ti() ? 6 : 5 ?>" class="empty-state">Nenhum chamado encontrado.</td></tr>
            <?php endif; ?>
            <?php foreach ($latest as $call): ?>
                <tr>
                    <td><a href="<?= h(app_url('chamados/editar.php?id=' . $call['id'])) ?>">#<?= str_pad((string) $call['id'], 4, '0', STR_PAD_LEFT) ?></a></td>
                    <?php if (is_ti()): ?><td><?= h($call['usuario_nome']) ?></td><?php endif; ?>
                    <td><?= h($call['titulo']) ?></td>
                    <td><span class="badge priority-<?= h($call['prioridade']) ?>"><?= h(priority_label($call['prioridade'])) ?></span></td>
                    <td><span class="badge status-<?= h($call['status']) ?>"><?= h(status_label($call['status'])) ?></span></td>
                    <td><?= h(date('d/m/Y H:i', strtotime($call['data_abertura']))) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
