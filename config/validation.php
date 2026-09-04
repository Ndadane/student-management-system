<?php
/**
 * Server-side input validation helpers.
 *
 * HTML attributes like `required` and `type="email"` can be bypassed
 * (disabled JS, direct POST requests, etc.), so every state-changing
 * form re-validates its inputs here before touching the database.
 */

/**
 * Validates a username: 3–50 chars, letters/numbers/underscore/dot only.
 */
function validate_username(string $username): ?string
{
    $username = trim($username);

    if ($username === '') {
        return 'Username is required.';
    }
    if (strlen($username) < 3 || strlen($username) > 50) {
        return 'Username must be between 3 and 50 characters.';
    }
    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
        return 'Username can only contain letters, numbers, dots, and underscores.';
    }

    return null; // valid
}

/**
 * Validates a person's full name: 2–100 chars, letters/spaces/hyphens/apostrophes only.
 * (Distinct from validate_username, which is stricter and meant for login usernames.)
 */
function validate_name(string $name): ?string
{
    $name = trim($name);

    if ($name === '') {
        return 'Name is required.';
    }
    if (strlen($name) < 2 || strlen($name) > 100) {
        return 'Name must be between 2 and 100 characters.';
    }
    if (!preg_match("/^[\p{L}\s'\-]+$/u", $name)) {
        return 'Name can only contain letters, spaces, hyphens, and apostrophes.';
    }

    return null;
}

/**
 * Validates an email address using PHP's built-in filter.
 */
function validate_email(string $email): ?string
{
    $email = trim($email);

    if ($email === '') {
        return 'Email is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Please enter a valid email address.';
    }

    return null;
}

/**
 * Validates a phone number: digits, spaces, +, -, () only, 7–15 digits.
 */
function validate_phone(string $phone): ?string
{
    $phone = trim($phone);

    if ($phone === '') {
        return 'Phone number is required.';
    }
    if (!preg_match('/^[0-9+\-\s()]+$/', $phone)) {
        return 'Phone number contains invalid characters.';
    }

    $digits_only = preg_replace('/\D/', '', $phone);
    if (strlen($digits_only) < 7 || strlen($digits_only) > 15) {
        return 'Phone number must have between 7 and 15 digits.';
    }

    return null;
}

/**
 * Validates a password: at least 8 characters.
 * (Kept intentionally simple — length is the single strongest practical
 * factor; complex composition rules mostly just push users towards
 * predictable substitutions like "Password1!".)
 */
function validate_password(string $password): ?string
{
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters long.';
    }

    return null;
}

/**
 * Runs a list of [value, validator_function] pairs and returns the first
 * error message found, or null if everything passed.
 *
 * Example:
 *   $error = validate_all([
 *       [$username, 'validate_username'],
 *       [$email, 'validate_email'],
 *   ]);
 */
function validate_all(array $checks): ?string
{
    foreach ($checks as [$value, $validator]) {
        $error = $validator($value);
        if ($error !== null) {
            return $error;
        }
    }

    return null;
}
