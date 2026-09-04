<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';

require_role('admin');

$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$result = $data->query("SELECT * FROM user WHERE usertype = 'student'");

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
        <h1>Student Data</h1>

        <?php if ($message): ?>
            <?php echo $message; /* pre-built alert markup, not raw user input */ ?>
        <?php endif; ?>
        

        <br><br>      
        <table border="1px">
            <tr>
                <th class="table_th">Username</th>
                <th class="table_th">Email</th>
                <th class="table_th">Phone</th>
                <th class="table_th">Delete</th>
                <th class="table_th">Update</th> 
            </tr>

            <?php while ($info = $result->fetch_assoc()): ?>

            <tr>
            <td class="table_td"> <?php echo htmlspecialchars($info['username']); ?> </td>
            <td class="table_td"> <?php echo htmlspecialchars($info['email']); ?> </td>
            <td class="table_td"> <?php echo htmlspecialchars($info['phone']); ?> </td>
            <td class="table_td"> <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?');" href="delete.php?student_id=<?php echo (int) $info['id']; ?>">Delete</a> </td>
            <td class="table_td"> <a class="btn btn-primary" href="update_student.php?student_id=<?php echo (int) $info['id']; ?>">Update</a> </td>
            </tr>
            <?php endwhile; ?>

        </table>
        </center>

    </div>

</body>
</html>
