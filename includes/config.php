<?php

declare(strict_types=1);

define('PROJECT_ROOT', dirname(__DIR__));

// Estes valores podem ser sobrescritos por variáveis de ambiente no servidor.
define('DB_HOST', getenv('CHAMADOS_DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('CHAMADOS_DB_PORT') ?: '3306');
define('DB_NAME', getenv('CHAMADOS_DB_NAME') ?: 'setor_ti');
define('DB_USER', getenv('CHAMADOS_DB_USER') ?: 'root');
define('DB_PASS', getenv('CHAMADOS_DB_PASS') !== false ? getenv('CHAMADOS_DB_PASS') : '');

function app_base_url(): string
{
    $configured = getenv('CHAMADOS_BASE_URL');
    if ($configured !== false && $configured !== '') {
        return '/' . trim($configured, '/');
    }

    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    $projectRoot = realpath(PROJECT_ROOT);

    if ($documentRoot && $projectRoot) {
        $documentRoot = str_replace('\\', '/', $documentRoot);
        $projectRoot = str_replace('\\', '/', $projectRoot);

        if (str_starts_with(strtolower($projectRoot), strtolower($documentRoot))) {
            return '/' . trim(substr($projectRoot, strlen($documentRoot)), '/');
        }
    }

    return '';
}

function app_url(string $path = ''): string
{
    return rtrim(app_base_url(), '/') . '/' . ltrim($path, '/');
}
