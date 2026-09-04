<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';
require 'config/audit.php';
require 'config/validation.php';

require_role('admin');

$student_id = (int) ($_GET['student_id'] ?? 0);
$update_error = null;

if (isset($_POST['update_student'])) {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    $checks = [
        [$username, 'validate_username'],
        [$email, 'validate_email'],
        [$phone, 'validate_phone'],
    ];
    if ($password !== '') {
        $checks[] = [$password, 'validate_password'];
    }
    $update_error = validate_all($checks);

    if ($update_error) {
        $_SESSION['message'] = "<div class='alert alert-danger'>" . htmlspecialchars($update_error) . "</div>";
        header('location: update_student.php?student_id=' . $student_id);
        exit;
    }

    if ($password !== '') {
        // A new password was entered — hash and update it too.
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $data->prepare(
            "UPDATE user SET username = ?, email = ?, phone = ?, password = ? WHERE id = ?"
        );
        $stmt->bind_param('ssssi', $username, $email, $phone, $hashed, $student_id);
    } else {
        // Leave the existing password untouched.
        $stmt = $data->prepare(
            "UPDATE user SET username = ?, email = ?, phone = ? WHERE id = ?"
        );
        $stmt->bind_param('sssi', $username, $email, $phone, $student_id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='alert alert-success'>Student Updated Successfully.</div>";
        log_audit_event('update', 'student', $student_id, "Updated student '$username'");
        header('location: view_student.php');
        exit;
    }

    error_log('Student update failed: ' . $stmt->error);
}

$stmt = $data->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>

   <?php

   include 'admin_css.php';
   ?>

   <style type="text/css">
       label
       {
           display: inline-block;
           width: 100px;
           text-align: right;
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

</head>
<body>

   <?php

   include 'admin_sidebar.php';
   ?>

   <div class="content">
        <center>
            <h1>Update Student</h1>

            <br><br>

            <?php while ($info = $result->fetch_assoc()): ?>
        <div class="div_deg"> 
            <form action="update_student.php?student_id=<?php echo (int) $student_id; ?>" method="POST">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($info['username']); ?>">
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($info['email']); ?>">
                </div>

                <div>
                    <label>Phone</label>
                    <input type="number" name="phone" value="<?php echo htmlspecialchars($info['phone']); ?>">
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current password">
                </div>

                <div>
                    <input class="btn btn-success" type="submit" name="update_student" value="Update Student">
                </div>

            </form>

            <?php endwhile; ?>
        </div> 
        </center>
   </div>

</body>
</html>
