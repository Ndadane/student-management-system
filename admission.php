<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';

require_role('admin');

$result = $data->query("SELECT * FROM admission");

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
    <center>
        <h1>Applied For Admission</h1>

        <table border="1px">            
            <tr>
                <th style="padding:20px; font-size:15px;">Name</th>
                <th style="padding:20px; font-size:15px;">Email</th>
                <th style="padding:20px; font-size:15px;">Phone</th>
                <th style="padding:20px; font-size:15px;">Message</th>
            </tr> 

            <?php while ($info = $result->fetch_assoc()): ?>

             <tr>
                <td style="padding:20px;">
                    <?php echo htmlspecialchars($info['name']); ?>  
                </td>
                <td style="padding:20px;">
                    <?php echo htmlspecialchars($info['email']); ?>
                </td>
                <td style="padding:20px;">
                    <?php echo htmlspecialchars($info['phone']); ?>
                </td>
                <td style="padding:20px;">
                    <?php echo htmlspecialchars($info['message']); ?>
                </td>
            </tr>
            <?php endwhile; ?>
           
        </table>
    </center>
    </div>

</body>
</html>
