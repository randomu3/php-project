<?php

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\AdminController;

$controller = new AdminController();
$controller->index();
