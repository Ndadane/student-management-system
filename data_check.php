<?php

require 'config/session.php';
require 'config/database.php';
require 'config/validation.php';

if (isset($_POST['apply'])) {

    $data_name    = trim($_POST['name'] ?? '');
    $data_email   = trim($_POST['email'] ?? '');
    $data_phone   = trim($_POST['phone'] ?? '');
    $data_message = trim($_POST['message'] ?? '');

    // Phone is optional on this public form, so only validate it if provided.
    $checks = [
        [$data_name, 'validate_name'],
        [$data_email, 'validate_email'],
    ];
    if ($data_phone !== '') {
        $checks[] = [$data_phone, 'validate_phone'];
    }
    $validation_error = validate_all($checks);

    if ($validation_error) {
        $_SESSION['message'] = "<div class='alert alert-danger'>" . htmlspecialchars($validation_error) . "</div>";
        header('location: index.php');
        exit;
    }

    $stmt = $data->prepare(
        "INSERT INTO admission (name, email, phone, message) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('ssss', $data_name, $data_email, $data_phone, $data_message);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Application Submitted Successfully';
        header('location: index.php');
        exit;
    }

    // Something went wrong — don't leak SQL/DB internals to the browser.
    error_log('Admission insert failed: ' . $stmt->error);
    $_SESSION['message'] = 'Application failed. Please try again.';
    header('location: index.php');
    exit;
}
