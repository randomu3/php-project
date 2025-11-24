<?php

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\ForgotPasswordController;

$controller = new ForgotPasswordController();
$controller->index();
