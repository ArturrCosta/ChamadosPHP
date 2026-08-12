<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamado - Setor de TI</title>
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
                <h1>Chamado #0012</h1>
                <p>Detalhes e atendimento do chamado.</p>
            </div>

            <a href="./" class="btn">Voltar</a>
        </div>

        <div class="form-card">

            <div class="form-group">
                <label>Funcionário</label>
                <input type="text" value="João Silva" disabled>
            </div>

            <div class="form-group">
                <label>Título</label>
                <input type="text" value="Computador não liga">
            </div>

            <div class="form-group">
                <label>Categoria</label>
                <input type="text" value="Computador" disabled>
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <textarea rows="5">O computador da sala 204 não está ligando.</textarea>
            </div>

            <div class="form-group">
                <label>Prioridade</label>
                <select>
                    <option>Alta</option>
                    <option>Média</option>
                    <option>Baixa</option>
                    <option>Urgente</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select>
                    <option>Aberto</option>
                    <option selected>Em andamento</option>
                    <option>Resolvido</option>
                    <option>Cancelado</option>
                </select>
            </div>

            <div class="form-group">
                <label>Observação da TI</label>
                <textarea rows="4"
                    placeholder="Digite uma observação sobre o atendimento..."></textarea>
            </div>

            <button class="btn">
                Salvar alterações
            </button>

        </div>

    </main>

</div>

</body>
</html>