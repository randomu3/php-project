<?php

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\ResetPasswordController;

$controller = new ResetPasswordController();
$controller->index();
