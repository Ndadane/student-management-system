<?php
/**
 * Audit logging helper.
 *
 * Call log_audit_event() after any state-changing action (create, update,
 * delete, login) so there's a durable record of who did what and when.
 */

function log_audit_event(string $action, string $entity_type, ?int $entity_id, string $details = ''): void
{
    global $data;

    $actor_id       = $_SESSION['id'] ?? null;
    $actor_username = $_SESSION['username'] ?? null;
    $ip_address     = $_SERVER['REMOTE_ADDR'] ?? null;

    $stmt = $data->prepare(
        "INSERT INTO audit_log (actor_id, actor_username, action, entity_type, entity_id, details, ip_address)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'ississs',
        $actor_id,
        $actor_username,
        $action,
        $entity_type,
        $entity_id,
        $details,
        $ip_address
    );

    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        error_log('Audit log insert failed: ' . $e->getMessage());
    }

    $stmt->close();
}
