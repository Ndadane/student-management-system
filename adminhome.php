<?php

require 'config/session.php';
require 'config/auth.php';

require_role('admin');

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

</head>
<body>

   <?php

    include 'admin_sidebar.php';
    ?>

    <div class="content">
        <h1>Welcome to Admin Dashboard</h1>
        <p>Manage all your admin tasks from here.</p>
    </div>

</body>
</html>
