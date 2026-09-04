<?php

<<<<<<< HEAD
require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';

require_role('student');

$name = $_SESSION['username'];

$stmt = $data->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param('s', $name);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();

if (isset($_POST['update_profile'])) {

    $semail = trim($_POST['email'] ?? '');
    $sphone = trim($_POST['phone'] ?? '');
    $spassword = $_POST['password'] ?? '';

    if ($spassword !== '') {
        $hashed = password_hash($spassword, PASSWORD_DEFAULT);
        $upd = $data->prepare("UPDATE user SET email = ?, phone = ?, password = ? WHERE username = ?");
        $upd->bind_param('ssss', $semail, $sphone, $hashed, $name);
    } else {
        $upd = $data->prepare("UPDATE user SET email = ?, phone = ? WHERE username = ?");
        $upd->bind_param('sss', $semail, $sphone, $name);
    }

    if ($upd->execute()) {
        header('location: student_profile.php');
        exit;
    }
    error_log('Profile update failed: ' . $upd->error);
=======
session_start();

    if(!isset($_SESSION['username']))
    {
        header("location:login.php");
    }

    elseif($_SESSION['usertype']=='admin')
    {
        header("location:login.php");
    }

$host="localhost";

$user="root";

$password="";

$db="schoolproject";

$data=mysqli_connect($host,$user,$password,$db);

$name=$_SESSION['username'];

$sql="SELECT * FROM user WHERE username='$name' ";

$result=mysqli_query($data,$sql);

$info=mysqli_fetch_assoc($result);

if(isset($_POST['update_profile']))
{
    
    $semail=$_POST['email'];
    $sphone=$_POST['phone'];
    $spassword=$_POST['password'];

    $sql2="UPDATE user SET email='$semail', phone='$sphone', password='$spassword' WHERE username='$name' ";

    $result2=mysqli_query($data,$sql2);

    if($result2)
    {
        header("location:student_profile.php");
    }

>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Student Profile</title>
=======
    <title>Admin Dashboard</title>
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9

    <?php
    include 'student_css.php';
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
            width: 500px;
            padding-top: 70px;
            padding-bottom: 70px;
        }
    </style>
   
</head>
<body>
    <?php
    include 'student_sidebar.php';
    ?>
   

    <div class="content">
        <center>
            <h1>Update Profile</h1>
            <br><br>
        <form action="#" method="POST">
        <div class="div_deg">

            <div>
                <label>Email</label>
<<<<<<< HEAD
                <input type="email" name="email" value="<?php echo htmlspecialchars($info['email']); ?>">
=======
                <input type="email" name="email" value="<?php echo "{$info['email']}" ?>">
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
            </div>

            <div>
                <label>Phone</label>
<<<<<<< HEAD
                <input type="number" name="phone" value="<?php echo htmlspecialchars($info['phone']); ?>">
=======
                <input type="number" name="phone" value="<?php echo "{$info['phone']}" ?>">
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
            </div>

            <div>
                <label>Password</label>
<<<<<<< HEAD
                <input type="password" name="password" placeholder="Leave blank to keep current password">
=======
                <input type="text" name="password" value="<?php echo "{$info['password']}" ?>">
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
            </div>

            <div>
                <input type="submit" class="btn btn-primary" name="update_profile" value="Update">  
            </div>
        </div>
        </form>
        </center>
    </div>

</body>
</html>
<<<<<<< HEAD
=======


>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
