<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuário - Setor de TI</title>
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

        <h1>Editar usuário</h1>

        <form class="form-card">

            <div class="form-group">
                <label>Nome completo</label>
                <input type="text" value="João Silva">
            </div>

            <div class="form-group">
                <label>Documento / Matrícula</label>
                <input type="text" value="123456" disabled>
            </div>

            <div class="form-group">
                <label>E-mail</label>
                <input type="email" value="joao@empresa.com">
            </div>

            <div class="form-group">
                <label>Cargo</label>
                <input type="text" value="Financeiro">
            </div>

            <div class="form-group">
                <label>Tipo de usuário</label>

                <select>
                    <option selected>Funcionário</option>
                    <option>TI</option>
                </select>
            </div>

            <button class="btn">
                Salvar alterações
            </button>

        </form>

    </main>

</div>

</body>
</html>