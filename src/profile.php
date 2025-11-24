<?php

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\ProfileController;

$controller = new ProfileController();
$controller->index();
