<?php

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\NotificationsController;

$controller = new NotificationsController();
$controller->index();
