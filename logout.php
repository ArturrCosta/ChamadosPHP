<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

$_SESSION = [];
session_destroy();
session_id('');
session_start();
session_regenerate_id(true);
set_flash('success', 'Sessão encerrada com sucesso.');
redirect('index.php');
