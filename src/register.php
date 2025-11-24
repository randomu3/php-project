<?php

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\RegisterController;

$controller = new RegisterController();
$controller->index();
