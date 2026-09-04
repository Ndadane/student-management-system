<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';
require 'config/audit.php';

require_role('admin');

$alert = null;
$allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

if (isset($_POST['add_teacher'])) {

    $t_name        = trim($_POST['name'] ?? '');
    $t_description = trim($_POST['description'] ?? '');
    $dst_db        = null;

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name  = $_FILES['image']['tmp_name'];
        $mime_type = mime_content_type($tmp_name);

        if (!in_array($mime_type, $allowed_types, true)) {
            $alert = 'Invalid image type. Please upload a JPG, PNG, WEBP, or GIF.';
        } else {
            // Generate a safe, random filename instead of trusting the
            // user-supplied one (which could contain path traversal or
            // executable extensions like .php).
            $ext = match ($mime_type) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
            };
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $dst      = './image/' . $filename;
            $dst_db   = 'image/' . $filename;

            if (!move_uploaded_file($tmp_name, $dst)) {
                $alert = 'Image upload failed.';
                $dst_db = null;
            }
        }
    }

    if (!$alert) {
        $stmt = $data->prepare("INSERT INTO teacher (name, description, image) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $t_name, $t_description, $dst_db);

        if ($stmt->execute()) {
            $alert = 'Teacher added successfully';
            log_audit_event('create', 'teacher', $data->insert_id, "Added teacher '$t_name'");
        } else {
            error_log('Add teacher failed: ' . $stmt->error);
            $alert = 'Failed to add teacher.';
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style type="text/css">
        .div_deg
        {
            background-color: skyblue;
            width: 500px;
            padding-top: 70px;
            padding-bottom: 70px;
            padding-left: 30px;
            padding-right: 30px;
        }
    </style>
   
    <?php

    include 'admin_css.php';
    ?>

</head>
<body>

   <?php

    include 'admin_sidebar.php';
    ?>

    <?php if ($alert): ?>
    <script>alert(<?php echo json_encode($alert); ?>);</script>
    <?php endif; ?>

    <div class="content">
        <center>
        <h1>Add Teacher</h1><br><br>
        <div class="div_deg">
            <form action="#" method="POST" enctype="multipart/form-data">
                <div>
                    <label>Teacher Name</label>
                    <input type="text" name="name" required>
                </div>

                <br>

                <div>
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>

                <br>

                <div>
                    <label>Image :</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <br>

                <div>
                    <input type="submit" name="add_teacher" value="Add Teacher" class="btn btn-primary">
                </div>
            </form>
        </div>
        </center>
    </div>

</body>
</html>
