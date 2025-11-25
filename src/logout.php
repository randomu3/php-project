<?php
/**
 * Logout Handler
 *
 * Completely destroys user session and redirects to login page.
 *
 * @package AuraUI
 */

// Prevent any output before headers
ob_start();

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get session name before destroying
$sessionName = session_name();
$sessionId = session_id();

// Clear all session variables
$_SESSION = [];

// Get cookie parameters
$cookieParams = session_get_cookie_params();

// Delete the session cookie with all possible variations
setcookie($sessionName, '', time() - 3600, '/');
setcookie($sessionName, '', time() - 3600, '/', '');
setcookie($sessionName, '', time() - 3600, '/', $_SERVER['HTTP_HOST'] ?? '');
setcookie($sessionName, '', time() - 3600, $cookieParams['path'], $cookieParams['domain']);

// Also try with secure and httponly flags
setcookie($sessionName, '', [
    'expires' => time() - 3600,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Remove from $_COOKIE superglobal
unset($_COOKIE[$sessionName]);

// Destroy the session
session_destroy();

// Delete session file manually if possible
$sessionPath = session_save_path();
if ($sessionPath && $sessionId) {
    $sessionFile = $sessionPath . '/sess_' . $sessionId;
    if (file_exists($sessionFile)) {
        @unlink($sessionFile);
    }
}

// Clear output buffer
ob_end_clean();

// Set aggressive no-cache headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Clear-Site-Data: \"cache\", \"cookies\", \"storage\"");

// Redirect to login with cache-busting parameter
header('Location: /login?t=' . time());
exit;
