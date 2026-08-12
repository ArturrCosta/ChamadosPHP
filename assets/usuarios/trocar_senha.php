<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha - Setor de TI</title>
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

        <h1>Redefinir senha</h1>
        <p>Defina uma nova senha para o usuário.</p>

        <form class="form-card">

            <div class="form-group">
                <label>Usuário</label>
                <input type="text" value="João Silva" disabled>
            </div>

            <div class="form-group">
                <label>Nova senha</label>
                <input type="password" required>
            </div>

            <div class="form-group">
                <label>Confirmar nova senha</label>
                <input type="password" required>
            </div>

            <button class="btn">
                Redefinir senha
            </button>

        </form>

    </main>

</div>

</body>
</html>