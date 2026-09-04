<?php

<<<<<<< HEAD
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
=======
error_reporting(0);
session_start();

    if(!isset($_SESSION['username']))
    {
        header("location:login.php");
    }

    elseif($_SESSION['usertype']=='student')
    {
        header("location:login.php");
    }

    $host="localhost";
    $user="root";
    $password="";
    $db="schoolproject";

    $data=mysqli_connect($host,$user,$password,$db); 

    $student_id = $_GET['student_id'];

    $sql = "SELECT * FROM user WHERE id='$student_id'";

    $result = mysqli_query($data, $sql);
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9

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

<<<<<<< HEAD
            <br><br>

            <?php while ($info = $result->fetch_assoc()): ?>
        <div class="div_deg"> 
            <form action="update_student.php?student_id=<?php echo (int) $student_id; ?>" method="POST">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($info['username']); ?>">
=======
            <?php
                if(isset($_SESSION['message']))
                {
                    echo $_SESSION['message'];
                }

                unset($_SESSION['message']);
            ?>

            <br><br>

            <?php
                while($info=$result->fetch_assoc())
                {
            ?>
        <div class="div_deg"> 
            <form action="" method="POST">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo "{$info['username']}"; ?>">
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
                </div>

                <div>
                    <label>Email</label>
<<<<<<< HEAD
                    <input type="email" name="email" value="<?php echo htmlspecialchars($info['email']); ?>">
=======
                    <input type="email" name="email" value="<?php echo "{$info['email']}"; ?>">
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
                </div>

                <div>
                    <label>Phone</label>
<<<<<<< HEAD
                    <input type="number" name="phone" value="<?php echo htmlspecialchars($info['phone']); ?>">
=======
                    <input type="number" name="phone" value="<?php echo "{$info['phone']}"; ?>">
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
                </div>

                <div>
                    <label>Password</label>
<<<<<<< HEAD
                    <input type="password" name="password" placeholder="Leave blank to keep current password">
=======
                    <input type="password" name="password" value="<?php echo "{$info['password']}"; ?>">
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
                </div>

                <div>
                    <input class="btn btn-success" type="submit" name="update_student" value="Update Student">
                </div>

            </form>

<<<<<<< HEAD
            <?php endwhile; ?>
=======
            <?php
                }
            ?>
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
        </div> 
        </center>
   </div>

</body>
</html>
<<<<<<< HEAD
=======

<?php

    if(isset($_POST['update_student']))
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];

        $sql2 = "UPDATE user SET username='$username', email='$email', phone='$phone', password='$password' WHERE id='$student_id' ";

        $result2 = mysqli_query($data, $sql2);

        if($result2)
        {
            $_SESSION['message'] = "<div class='alert alert-success'>Student Updated Successfully.</div>";
            header("location:view_student.php");
        }
        else
        {
            echo "Update Failed";
        }
    }

?>
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
