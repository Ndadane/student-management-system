<?php
/**
 * Simple role-guard helpers.
 * IMPORTANT: header() does not stop script execution by itself —
 * every redirect here is followed by exit, otherwise the rest of the
 * "protected" page still runs and can render/act even for logged-out users.
 */

function require_login()
{
    if (!isset($_SESSION['username'])) {
        header('location: login.php');
        exit;
    }
}

function require_role($allowed_role)
{
    require_login();
    if ($_SESSION['usertype'] !== $allowed_role) {
        header('location: login.php');
        exit;
    }
}
