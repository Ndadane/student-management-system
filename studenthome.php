<?php

require 'config/session.php';
require 'config/auth.php';

require_role('student');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <?php
    include 'student_css.php';
    ?>
   
</head>
<body>
    <?php
    include 'student_sidebar.php';
    ?>

    <div class="content">
        <h1>Welcome to your Student Dashboard</h1>
    </div>

</body>
</html>
