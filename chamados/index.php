<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados - Setor de TI</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <h2>Setor de TI</h2>

        <nav>
            <a href="../dashboard/">Dashboard</a>
            <a href="./">Chamados</a>
            <a href="novo.php">Novo chamado</a>
            <a href="../usuarios/">Usuários</a>
            <a href="../relatorios/">Relatórios</a>
            <a href="../logout.php">Sair</a>
        </nav>

    </aside>

    <main class="content">

        <div class="section-header">
            <div>
                <h1>Chamados</h1>
                <p>Gerenciamento dos chamados de suporte.</p>
            </div>

            <a href="novo.php" class="btn">
                + Novo chamado
            </a>
        </div>

        <div class="filters">

            <input type="text" placeholder="Pesquisar chamado...">

            <select>
                <option>Todos os status</option>
                <option>Aberto</option>
                <option>Em andamento</option>
                <option>Resolvido</option>
            </select>

            <select>
                <option>Todas as prioridades</option>
                <option>Baixa</option>
                <option>Média</option>
                <option>Alta</option>
                <option>Urgente</option>
            </select>

        </div>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Funcionário</th>
                    <th>Problema</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>#0012</td>
                    <td>João Silva</td>
                    <td>Computador não liga</td>
                    <td>Alta</td>
                    <td>Em andamento</td>
                    <td>
                        <a href="editar.php">Visualizar</a>
                    </td>
                </tr>

                <tr>
                    <td>#0011</td>
                    <td>Maria Souza</td>
                    <td>Impressora</td>
                    <td>Média</td>
                    <td>Aberto</td>
                    <td>
                        <a href="editar.php">Visualizar</a>
                    </td>
                </tr>

            </tbody>

        </table>

    </main>

</div>

</body>
</html>