<<<<<<< HEAD
<?php

require 'config/session.php';
require 'config/database.php';
require 'config/validation.php';

if (isset($_POST['apply'])) {

    $data_name    = trim($_POST['name'] ?? '');
    $data_email   = trim($_POST['email'] ?? '');
    $data_phone   = trim($_POST['phone'] ?? '');
    $data_message = trim($_POST['message'] ?? '');

    // Phone is optional on this public form, so only validate it if provided.
    $checks = [
        [$data_name, 'validate_name'],
        [$data_email, 'validate_email'],
    ];
    if ($data_phone !== '') {
        $checks[] = [$data_phone, 'validate_phone'];
    }
    $validation_error = validate_all($checks);

    if ($validation_error) {
        $_SESSION['message'] = "<div class='alert alert-danger'>" . htmlspecialchars($validation_error) . "</div>";
        header('location: index.php');
        exit;
    }

    $stmt = $data->prepare(
        "INSERT INTO admission (name, email, phone, message) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('ssss', $data_name, $data_email, $data_phone, $data_message);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Application Submitted Successfully';
        header('location: index.php');
        exit;
    }

    // Something went wrong — don't leak SQL/DB internals to the browser.
    error_log('Admission insert failed: ' . $stmt->error);
    $_SESSION['message'] = 'Application failed. Please try again.';
    header('location: index.php');
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

if($data===false)
{
    die("connection error");
}

if(isset($_POST['apply']))
{
    $data_name=$_POST['name'];
    $data_email=$_POST['email'];
    $data_phone=$_POST['phone'];
    $data_message=$_POST['message'];


    $sql="INSERT INTO admission(name,email,phone,message)
        VALUES ('$data_name','$data_email','$data_phone','$data_message')";

        $result=mysqli_query($data,$sql);
        if($result)
        {
            $_SESSION['message']="Application Submitted Successfully";
            header("location:index.php");
        }

        else
        {
          
             echo "Apply Failed<br>";
             echo "Error: " . mysqli_error($data);  // This will show the actual error
             echo "<br>SQL: " . $sql;  // This will show the SQL query
        }

}


?>

>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
