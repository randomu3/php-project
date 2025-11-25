<?php

// Prevent caching of authenticated pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

require_once __DIR__ . '/config.php';

use AuraUI\Controllers\ProfileController;

$controller = new ProfileController();
$controller->index();
