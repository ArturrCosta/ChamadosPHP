<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function current_user(): ?array
{
    $user = $_SESSION['usuario'] ?? null;
    return is_array($user) ? $user : null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_ti(): bool
{
    return (current_user()['tipo'] ?? null) === 'ti';
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('error', 'Faça login para acessar essa página.');
        redirect('index.php');
    }
}

function require_ti(): void
{
    require_login();

    if (!is_ti()) {
        set_flash('error', 'Acesso permitido somente para a equipe de TI.');
        redirect('dashboard/');
    }
}
