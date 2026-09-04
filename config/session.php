<?php
/**
 * Secure session bootstrap.
 * Include this INSTEAD OF calling session_start() directly.
 *
 * - HttpOnly: JavaScript cannot read the session cookie (mitigates XSS-based session theft)
 * - SameSite=Lax: cookie isn't sent on most cross-site requests (mitigates CSRF)
 * - Secure: cookie only sent over HTTPS (set to true once you deploy with HTTPS)
 * - Session timeout: auto-logout after a period of inactivity
 */

$session_timeout_seconds = 1800; // 30 minutes of inactivity

session_set_cookie_params([
    'lifetime' => 0,        // expires when browser closes
    'path'     => '/',
    'domain'   => '',
    'secure'   => false,    // set to true once served over HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

// Enforce inactivity timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout_seconds) {
    session_unset();
    session_destroy();
    session_start(); // start a fresh, empty session so the page doesn't error
}
$_SESSION['last_activity'] = time();
