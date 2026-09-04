<<<<<<< HEAD
<?php

require 'config/session.php';
require 'config/database.php';
require 'config/auth.php';
require 'config/audit.php';

require_role('admin');

if (isset($_GET['student_id'])) {

    $user_id = (int) $_GET['student_id'];

    // Capture the username before deleting, so the audit trail still
    // reads clearly after the row is gone.
    $lookup = $data->prepare("SELECT username FROM user WHERE id = ?");
    $lookup->bind_param('i', $user_id);
    $lookup->execute();
    $deleted_user = $lookup->get_result()->fetch_assoc();
    $lookup->close();

    $stmt = $data->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Student Deleted Successfully.';
        $deleted_username = $deleted_user['username'] ?? "id $user_id";
        log_audit_event('delete', 'student', $user_id, "Deleted student '$deleted_username'");
    }

    header('location: view_student.php');
    exit;
}
=======
<?php

session_start();

$host="localhost";
$user="root";
$password="";
$db="schoolproject";

$data=mysqli_connect($host,$user,$password,$db);

if($_GET['student_id'])
    {
        $user_id=$_GET['student_id'];

        $sql="DELETE FROM user WHERE id='$user_id'";

        $result=mysqli_query($data,$sql);

        if($result)
            {
                $_SESSION['message']="Student Deleted Successfully.";
                header("location:view_student.php");
            }

    }


?>

>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
