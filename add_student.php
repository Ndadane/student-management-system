<<<<<<< HEAD
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
=======
<?php

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

    if(isset($_POST['add_student']))
        {
            $username=$_POST['name'];
            $user_email=$_POST['email'];
            $user_phone=$_POST['phone'];
            $user_password=$_POST['password'];
            $usertype="student"; 

            $check="SELECT * FROM user WHERE username='$username'";
            $check_user=mysqli_query($data,$check);

            $row_count=mysqli_num_rows($check_user);

            if($row_count==1)
            {
                echo "<script type='text/javascript'>
                alert('Username Already Exists. Try Another Username');
                </script>";
            }

            else
            {
               
            



            $sql="INSERT INTO user(username,email,phone,usertype,password) 
             VALUES ('$username','$user_email','$user_phone','$usertype','$user_password')";

             $result=mysqli_query($data,$sql);
             if($result)
             {
                echo "<script type='text/javascript'>
                alert('Student Added Successfully');;
                </script>";
             }

             else
                {
                    echo "Upload Failed";
                }

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

    <div class="content">
        <center>
        <h1>Add Student</h1>

        <div class="div_deg">
            <form action="#" method="POST">
                <div>
                    <label>Username</label>
                    <input type="text" name="name">
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
                    <input type="text" name="password">
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

>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
