<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';

require_role('admin');

// Simple pagination so this stays usable once the log grows large.
$page     = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 50;
$offset   = ($page - 1) * $per_page;

$total_result = $data->query("SELECT COUNT(*) AS total FROM audit_log");
$total = (int) $total_result->fetch_assoc()['total'];
$total_pages = max(1, (int) ceil($total / $per_page));

$stmt = $data->prepare(
    "SELECT * FROM audit_log ORDER BY created_at DESC LIMIT ? OFFSET ?"
);
$stmt->bind_param('ii', $per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log</title>

    <?php include 'admin_css.php'; ?>

    <style type="text/css">
        table { border-collapse: collapse; width: 95%; margin: 0 auto; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; font-size: 14px; text-align: left; }
        th { background-color: #f0f0f0; }
        .action-create { color: #1a7a1a; font-weight: bold; }
        .action-update { color: #b58900; font-weight: bold; }
        .action-delete { color: #b00020; font-weight: bold; }
        .action-login, .action-login_failed { color: #444; font-weight: bold; }
        .pagination { text-align: center; margin: 20px 0; }
        .pagination a { margin: 0 5px; text-decoration: none; }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <center>
        <h1>Audit Log</h1>
        <p><?php echo $total; ?> total events</p>

        <table>
            <tr>
                <th>Time</th>
                <th>Actor</th>
                <th>Action</th>
                <th>Entity</th>
                <th>Details</th>
                <th>IP Address</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td><?php echo htmlspecialchars($row['actor_username'] ?? 'unknown'); ?></td>
                <td class="action-<?php echo htmlspecialchars($row['action']); ?>">
                    <?php echo htmlspecialchars($row['action']); ?>
                </td>
                <td><?php echo htmlspecialchars($row['entity_type']); ?> #<?php echo htmlspecialchars((string) $row['entity_id']); ?></td>
                <td><?php echo htmlspecialchars($row['details'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address'] ?? ''); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="audit_log.php?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>
            Page <?php echo $page; ?> of <?php echo $total_pages; ?>
            <?php if ($page < $total_pages): ?>
                <a href="audit_log.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
        </center>
    </div>

</body>
</html>
