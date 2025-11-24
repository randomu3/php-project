<?php

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\LoginController;

$controller = new LoginController();
$controller->index();
