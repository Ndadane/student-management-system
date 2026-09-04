<?php
/**
 * Centralized database connection.
 * Include this instead of hardcoding credentials in every file.
 */

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'schoolproject');

try {
    $data = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $data->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Never leak DB details to the browser.
    error_log('DB connection failed: ' . $e->getMessage());
    die('A database error occurred. Please try again later.');
}
