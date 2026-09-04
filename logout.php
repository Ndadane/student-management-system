<<<<<<< HEAD

<?php
require 'config/session.php';
session_unset();
session_destroy();
header('location: login.php');
exit;
=======
<?php

session_start();
session_destroy();

header("location:login.php");










?>
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
