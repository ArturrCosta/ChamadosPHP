<?php $navUser = current_user(); ?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="<?= h(app_url('assets/img/logo.png')) ?>" alt="Logo da empresa">
        <h2>Setor de TI</h2>
    </div>

    <p class="sidebar-user">
        <?= h($navUser['nome'] ?? '') ?><br>
        <small><?= h(user_type_label($navUser['tipo'] ?? 'funcionario')) ?></small>
    </p>

    <nav>
        <a href="<?= h(app_url('dashboard/')) ?>">Dashboard</a>
        <a href="<?= h(app_url('chamados/')) ?>">Chamados</a>
        <a href="<?= h(app_url('chamados/novo.php')) ?>">Novo chamado</a>
        <?php if (is_ti()): ?>
            <a href="<?= h(app_url('usuarios/')) ?>">Usuários</a>
            <a href="<?= h(app_url('relatorios/')) ?>">Relatórios</a>
        <?php endif; ?>
        <a href="<?= h(app_url('logout.php')) ?>">Sair</a>
    </nav>
</aside>
