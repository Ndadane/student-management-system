<?php

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
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo "{$info['email']}"; ?>">
                </div>

                <div>
                    <label>Phone</label>
                    <input type="number" name="phone" value="<?php echo "{$info['phone']}"; ?>">
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" value="<?php echo "{$info['password']}"; ?>">
                </div>

                <div>
                    <input class="btn btn-success" type="submit" name="update_student" value="Update Student">
                </div>

            </form>

            <?php
                }
            ?>
        </div> 
        </center>
   </div>

</body>
</html>

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
