
<?php
require 'config/session.php';
session_unset();
session_destroy();
header('location: login.php');
exit;
