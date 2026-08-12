<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo chamado - Setor de TI</title>

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

        <h1>Novo chamado</h1>

        <p>Descreva o problema que está acontecendo.</p>

        <form class="form-card">

            <div class="form-group">
                <label for="titulo">Título</label>
                <input
                    type="text"
                    id="titulo"
                    placeholder="Ex.: Computador não liga"
                    required
                >
            </div>

            <div class="form-group">

                <label for="categoria">Tipo de problema</label>

                <select id="categoria" required>
                    <option value="">Selecione</option>
                    <option>Computador</option>
                    <option>Internet</option>
                    <option>Impressora</option>
                    <option>Software</option>
                    <option>Rede</option>
                    <option>Acesso / Senha</option>
                    <option>Outro</option>
                </select>

            </div>

            <div class="form-group">

                <label for="local">Local</label>

                <input
                    type="text"
                    id="local"
                    placeholder="Ex.: Sala 204"
                >

            </div>

            <div class="form-group">

                <label for="descricao">Descrição</label>

                <textarea
                    id="descricao"
                    rows="6"
                    placeholder="Explique o problema..."
                    required
                ></textarea>

            </div>

            <div class="info-box">
                A prioridade será definida automaticamente pelo sistema.
            </div>

            <button type="submit" class="btn">
                Enviar chamado
            </button>

        </form>

    </main>

</div>

</body>
</html>