<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Setor de TI</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <h2>Setor de TI</h2>

        <nav>
            <a href="../dashboard/">Dashboard</a>
            <a href="../chamados/">Chamados</a>
            <a href="../chamados/novo.php">Novo chamado</a>
            <a href="../usuarios/">Usuários</a>
            <a href="../relatorios/">Relatórios</a>
            <a href="../logout.php">Sair</a>
        </nav>

    </aside>

    <main class="content">

        <header class="page-header">
            <div>
                <h1>Dashboard</h1>
                <p>Bem-vindo ao Setor de TI da Firma.</p>
            </div>
        </header>

        <section class="cards">

            <div class="card">
                <span>Chamados abertos</span>
                <strong>8</strong>
            </div>

            <div class="card">
                <span>Em andamento</span>
                <strong>5</strong>
            </div>

            <div class="card">
                <span>Resolvidos</span>
                <strong>32</strong>
            </div>

            <div class="card">
                <span>Urgentes</span>
                <strong>2</strong>
            </div>

        </section>

        <section class="dashboard-section">

            <div class="section-header">
                <h2>Últimos chamados</h2>

                <a href="../chamados/novo.php" class="btn">
                    Novo chamado
                </a>
            </div>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Problema</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>#0012</td>
                        <td>Computador não liga</td>
                        <td>Alta</td>
                        <td>Em andamento</td>
                    </tr>

                    <tr>
                        <td>#0011</td>
                        <td>Problema na impressora</td>
                        <td>Média</td>
                        <td>Aberto</td>
                    </tr>

                    <tr>
                        <td>#0010</td>
                        <td>Instalação de software</td>
                        <td>Baixa</td>
                        <td>Resolvido</td>
                    </tr>

                </tbody>

            </table>

        </section>

    </main>

</div>

</body>
</html>