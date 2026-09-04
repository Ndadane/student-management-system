<?php

<<<<<<< HEAD
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
=======
session_start();
error_reporting(0);

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

    $sql="SELECT * FROM teacher";

    $result=mysqli_query($data,$sql);

    if($_GET['teacher_id'])
    {
        $t_id=$_GET['teacher_id'];

        $sql2="DELETE FROM teacher WHERE id='$t_id' ";

        $result2=mysqli_query($data,$sql2);

        if($result2)
        {
            header("location:admin_view_teacher.php");
        }
    }
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9

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

<<<<<<< HEAD
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
=======
            <?php
            while($info=$result->fetch_assoc())
            {
            ?>
            <tr>
                <td class="table_td">
                <?php echo "{$info['name']}" ?>
                
                </td>
                <td class="table_td">
                <?php echo "{$info['description']}" ?>
                
                </td>
                <td class="table_td">
                <img src=" <?php echo "{$info['image']}" ?> " height="100" width="100"/>
                
                </td>
                <td class="table_td">

                    <?php

                    echo"
                    <a onclick='return confirm(\"Are you sure you want to delete this teacher?\")' class='btn btn-danger' href='admin_view_teacher.php?teacher_id={$info['id']}'>Delete</a> ";

                    ?>
                </td>

                <td class="table_td">

                    <?php

                    echo"
                    <a class='btn btn-primary' href='admin_update_teacher.php?teacher_id={$info['id']}'>Update</a> ";

                    ?>
                </td>
            </tr>

            <?php
            }
            ?>
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
            
        </table>
        </center>
    </div>

</body>
</html>
<<<<<<< HEAD
=======

>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
