<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';
require 'config/audit.php';

require_role('admin');

$allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

if (isset($_POST['update_teacher'])) {

    $id     = (int) ($_POST['id'] ?? 0);
    $t_name = trim($_POST['name'] ?? '');
    $t_des  = trim($_POST['description'] ?? '');

    $new_image = null;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name  = $_FILES['image']['tmp_name'];
        $mime_type = mime_content_type($tmp_name);

        if (in_array($mime_type, $allowed_types, true)) {
            $ext = match ($mime_type) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
            };
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            move_uploaded_file($tmp_name, './image/' . $filename);
            $new_image = 'image/' . $filename;
        }
    }

    if ($new_image) {
        $stmt = $data->prepare("UPDATE teacher SET name = ?, description = ?, image = ? WHERE id = ?");
        $stmt->bind_param('sssi', $t_name, $t_des, $new_image, $id);
    } else {
        $stmt = $data->prepare("UPDATE teacher SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param('ssi', $t_name, $t_des, $id);
    }

    if ($stmt->execute()) {
        log_audit_event('update', 'teacher', $id, "Updated teacher '$t_name'");
        header('location: admin_view_teacher.php');
        exit;
    }
    error_log('Update teacher failed: ' . $stmt->error);
}

$teacher_id = (int) ($_GET['teacher_id'] ?? 0);
$stmt = $data->prepare("SELECT * FROM teacher WHERE id = ?");
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();

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
        label
        {
            display: inline-block;
            width: 150px;
            text-align: right;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .form_deg
        {
            background-color: skyblue;
            width: 600px;
            padding-top: 70px;
            padding-bottom: 70px         
        }
    </style>    

</head>
<body>

   <?php

    include 'admin_sidebar.php';
    ?>

    <div class="content">
        <center>
        <h1>Update Teacher Information</h1>

        <?php if ($info): ?>
        <form class="form_deg" action="admin_update_teacher.php" method="POST" enctype="multipart/form-data"> 

        <input name="id" value="<?php echo (int) $info['id']; ?>" hidden>    

            <div>
                <label>Teacher Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($info['name']); ?>">   
            </div>

            <br>

            <div>
                <label>About Teacher</label>
                <textarea name="description"><?php echo htmlspecialchars($info['description']); ?></textarea>
            </div>

            <br>

            <div>
                <label>Teacher Old Image</label>
                <img src="<?php echo htmlspecialchars($info['image']); ?>" height="100px" width="100px">
            </div>

            <br>

            <div>
                <label>Upload New Teacher Image</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <br>

            <div>
                <input class="btn btn-success" type="submit" name="update_teacher" value="Update">
            </div>
        </form>
        <?php else: ?>
            <p>Teacher not found.</p>
        <?php endif; ?>
        </center>
    </div>

</body>
</html>
