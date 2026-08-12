<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo usuário - Setor de TI</title>
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

        <h1>Novo usuário</h1>
        <p>Cadastre um novo usuário no sistema.</p>

        <form class="form-card">

            <div class="form-group">
                <label>Nome completo</label>
                <input type="text" placeholder="Nome do funcionário" required>
            </div>

            <div class="form-group">
                <label>Documento / Matrícula</label>
                <input type="text" placeholder="Documento ou matrícula" required>
            </div>

            <div class="form-group">
                <label>E-mail</label>
                <input type="email" placeholder="email@empresa.com" required>
            </div>

            <div class="form-group">
                <label>Cargo</label>
                <input type="text" placeholder="Cargo do funcionário">
            </div>

            <div class="form-group">
                <label>Tipo de usuário</label>

                <select required>
                    <option value="">Selecione</option>
                    <option>Funcionário</option>
                    <option>TI</option>
                </select>
            </div>

            <div class="form-group">
                <label>Senha inicial</label>
                <input type="password" required>
            </div>

            <button class="btn">
                Cadastrar usuário
            </button>

        </form>

    </main>

</div>

</body>
</html>