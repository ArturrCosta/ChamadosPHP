<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_login();

$pageTitle = $pageTitle ?? 'Setor de TI da Firma';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?> - Setor de TI</title>
    <link rel="stylesheet" href="<?= h(app_url('assets/css/style.css')) ?>">
</head>
<body>
<div class="layout">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="content">
        <?php if ($flash): ?>
            <div class="message <?= $flash['type'] === 'success' ? 'message-success' : 'message-error' ?>" role="alert">
                <?= h($flash['message']) ?>
            </div>
        <?php endif; ?>
