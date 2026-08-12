<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuários - Setor de TI</title>

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
            <a href="./">Usuários</a>
            <a href="../relatorios/">Relatórios</a>
            <a href="../logout.php">Sair</a>
        </nav>

    </aside>

    <main class="content">

        <div class="section-header">

            <div>
                <h1>Usuários</h1>
                <p>Gerenciamento dos usuários da empresa.</p>
            </div>

            <a href="novo.php" class="btn">
                + Novo usuário
            </a>

        </div>

        <table>

            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Documento</th>
                    <th>Cargo</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>João Silva</td>
                    <td>123456</td>
                    <td>Financeiro</td>
                    <td>Funcionário</td>
                    <td>
                        <a href="editar.php">Editar</a>
                        |
                        <a href="trocar_senha.php">Senha</a>
                        |
                        <a href="#" onclick="return confirm('Deseja remover este usuário?')">
                            Excluir
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>Administrador TI</td>
                    <td>999999</td>
                    <td>TI</td>
                    <td>TI</td>
                    <td>
                        <a href="editar.php">Editar</a>
                        |
                        <a href="trocar_senha.php">Senha</a>
                        |
                        <a href="#" onclick="return confirm('Deseja remover este usuário?')">
                            Excluir
                        </a>
                    </td>
                </tr>

            </tbody>

        </table>

    </main>

</div>

</body>
</html>