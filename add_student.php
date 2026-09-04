<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';
require 'config/audit.php';
require 'config/validation.php';

require_role('admin');

$alert = null;

if (isset($_POST['add_student'])) {

    $username      = trim($_POST['name'] ?? '');
    $user_email    = trim($_POST['email'] ?? '');
    $user_phone    = trim($_POST['phone'] ?? '');
    $user_password = $_POST['password'] ?? '';

    $validation_error = validate_all([
        [$username, 'validate_username'],
        [$user_email, 'validate_email'],
        [$user_phone, 'validate_phone'],
        [$user_password, 'validate_password'],
    ]);

    $check = $data->prepare("SELECT id FROM user WHERE username = ?");
    $check->bind_param('s', $username);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;
    $check->close();

    if ($validation_error) {
        $alert = $validation_error;
    } elseif ($exists) {
        $alert = 'Username Already Exists. Try Another Username';
    } else {
        $hashed = password_hash($user_password, PASSWORD_DEFAULT);
        $usertype = 'student';

        $stmt = $data->prepare(
            "INSERT INTO user (username, email, phone, usertype, password) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssss', $username, $user_email, $user_phone, $usertype, $hashed);

        if ($stmt->execute()) {
            $alert = 'Student Added Successfully';
            log_audit_event('create', 'student', $data->insert_id, "Added student '$username'");
        } else {
            $alert = 'Upload Failed';
        }
        $stmt->close();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <style type="text/css">
        label
        {
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 10px;         
        }

        .div_deg 
        {
            background-color: skyblue;
            width: 400px;
            padding-top: 70px;
            padding-bottom: 70px;
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
    <script type="text/javascript">
        alert(<?php echo json_encode($alert); ?>);
    </script>
    <?php endif; ?>

    <div class="content">
        <center>
        <h1>Add Student</h1>

        <div class="div_deg">
            <form action="#" method="POST">
                <div>
                    <label>Username</label>
                    <input type="text" name="name" required>
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email">
                </div>

                <div>
                    <label>Phone</label>
                    <input type="number" name="phone">
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div>
                    <input type="submit" class="btn btn-primary" name="add_student" value="Add Student"> 
                </div>
            </form>
        </div>
        </center>
    </div>

</body>
</html>
