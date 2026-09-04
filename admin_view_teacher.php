<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';
require 'config/audit.php';

require_role('admin');

if (isset($_GET['teacher_id'])) {
    $t_id = (int) $_GET['teacher_id'];

    $lookup = $data->prepare("SELECT name FROM teacher WHERE id = ?");
    $lookup->bind_param('i', $t_id);
    $lookup->execute();
    $deleted_teacher = $lookup->get_result()->fetch_assoc();
    $lookup->close();

    $stmt = $data->prepare("DELETE FROM teacher WHERE id = ?");
    $stmt->bind_param('i', $t_id);
    if ($stmt->execute()) {
        $deleted_name = $deleted_teacher['name'] ?? "id $t_id";
        log_audit_event('delete', 'teacher', $t_id, "Deleted teacher '$deleted_name'");
    }
    header('location: admin_view_teacher.php');
    exit;
}

$result = $data->query("SELECT * FROM teacher");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
   
    <?php

    include 'admin_css.php';
    ?>

    <style type="text/css">
        .table_th
        {
            padding: 20px;
            font-size: 20px;
        }

        .table_td
        {
            padding: 20px;
            background-color: skyblue;
        }
    </style>

        

</head>
<body>

   <?php

    include 'admin_sidebar.php';
    ?>

    <div class="content">
        <center>
        <h1>View All Teacher Data</h1>

        <table border="1px">
            <tr>

                <th class="table_th">Teacher Name</th>
                <th class="table_th">About Teacher</th>
                <th class="table_th">Image</th>
                <th class="table_th">Delete</th>
                <th class="table_th">Update</th>  

            </tr>

            <?php while ($info = $result->fetch_assoc()): ?>
            <tr>
                <td class="table_td">
                <?php echo htmlspecialchars($info['name']); ?>
                </td>
                <td class="table_td">
                <?php echo htmlspecialchars($info['description']); ?>
                </td>
                <td class="table_td">
                <img src="<?php echo htmlspecialchars($info['image']); ?>" height="100" width="100"/>
                </td>
                <td class="table_td">
                    <a onclick="return confirm('Are you sure you want to delete this teacher?')" class="btn btn-danger" href="admin_view_teacher.php?teacher_id=<?php echo (int) $info['id']; ?>">Delete</a>
                </td>

                <td class="table_td">
                    <a class="btn btn-primary" href="admin_update_teacher.php?teacher_id=<?php echo (int) $info['id']; ?>">Update</a>
                </td>
            </tr>
            <?php endwhile; ?>
            
        </table>
        </center>
    </div>

</body>
</html>
