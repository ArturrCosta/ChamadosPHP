<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    try {
        initialize_database();
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        error_log('Erro de conexão com o banco: ' . $exception->getMessage());
        throw new RuntimeException(
            'Não foi possível conectar ao banco de dados. Verifique a configuração e a importação do arquivo SQL.'
        );
    }

    return $pdo;
}

/** Cria o banco e importa o esquema na primeira execucao. */
function initialize_database(): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', DB_NAME)) {
        throw new RuntimeException('O nome configurado para o banco de dados e invalido.');
    }

    $server = new PDO(
        sprintf('mysql:host=%s;port=%s;charset=utf8mb4', DB_HOST, DB_PORT),
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $server->exec(sprintf(
        'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
        DB_NAME
    ));

    $connection = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME),
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    if (!$connection->query("SHOW TABLES LIKE 'usuarios'")->fetchColumn()) {
        $sql = file_get_contents(PROJECT_ROOT . '/database.sql');
        if ($sql === false) {
            throw new RuntimeException('Nao foi possivel ler o arquivo database.sql.');
        }
        $sql = preg_replace('/^CREATE DATABASE.*?;\s*/is', '', $sql, 1);
        $sql = preg_replace('/^USE\s+[^;]+;\s*/i', '', (string) $sql, 1);
        $connection->exec((string) $sql);
    }

    $initialized = true;
}
