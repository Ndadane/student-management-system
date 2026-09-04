<?php

require 'config/session.php';
require 'config/database.php';
require 'config/audit.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    $stmt = $data->prepare("SELECT id, username, password, usertype FROM user WHERE username = ?");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // password_verify() safely handles both new bcrypt hashes and (via the
    // legacy fallback below) any old plaintext rows that haven't been
    // migrated yet.
    $valid = false;
    if ($row) {
        if (password_verify($pass, $row['password'])) {
            $valid = true;
        } elseif ($row['password'] === $pass) {
    $valid = true;
    $newHash = password_hash($pass, PASSWORD_DEFAULT);
    $upd = $data->prepare("UPDATE user SET password = ? WHERE id = ?");
    $upd->bind_param('si', $newHash, $row['id']);
    $upd->execute();
    $upd->close();
        }
    }

    if ($valid) {
        session_regenerate_id(true);
        $_SESSION['id']       = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['usertype'] = $row['usertype'];

        log_audit_event('login', 'user', $row['id'], 'Successful login');

        if ($row['usertype'] === 'student') {
            header('location: studenthome.php');
        } else {
            header('location: adminhome.php');
        }
        exit;
    }

    log_audit_event('login_failed', 'user', $row['id'] ?? null, "Failed login attempt for username '$name'");

    $_SESSION['loginMessage'] = 'Username or password do not match';
    header('location: login.php');
    exit;
}
