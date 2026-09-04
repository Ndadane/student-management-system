<<<<<<< HEAD
<?php

require 'config/session.php';
require 'config/database.php';
require 'config/audit.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    $stmt = $data->prepare("SELECT id, username, password, usertype FROM user WHERE username = ?");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // password_verify() safely handles both new bcrypt hashes and (via the
    // legacy fallback below) any old plaintext rows that haven't been
    // migrated yet.
    $valid = false;
    if ($row) {
        if (password_verify($pass, $row['password'])) {
            $valid = true;
        } elseif ($row['password'] === $pass) {
    $valid = true;
    $newHash = password_hash($pass, PASSWORD_DEFAULT);
    $upd = $data->prepare("UPDATE user SET password = ? WHERE id = ?");
    $upd->bind_param('si', $newHash, $row['id']);
    $upd->execute();
    $upd->close();
        }
    }

    if ($valid) {
        session_regenerate_id(true);
        $_SESSION['id']       = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['usertype'] = $row['usertype'];

        log_audit_event('login', 'user', $row['id'], 'Successful login');

        if ($row['usertype'] === 'student') {
            header('location: studenthome.php');
        } else {
            header('location: adminhome.php');
        }
        exit;
    }

    log_audit_event('login_failed', 'user', $row['id'] ?? null, "Failed login attempt for username '$name'");

    $_SESSION['loginMessage'] = 'Username or password do not match';
    header('location: login.php');
    exit;
}
=======
<?php

error_reporting(0);
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

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $name = $_POST['username'];

    $pass = $_POST['password'];

    $sql="select * from user where username='".$name."' AND password='".$pass."' ";

    $result=mysqli_query($data,$sql);

    $row=mysqli_fetch_array($result);

    if($row["usertype"]=="student")
    {
        
        $_SESSION['username']=$name;
        $_SESSION['usertype']="student";
        header("location:studenthome.php");
    }

    elseif($row["usertype"]=="admin")
    {
        $_SESSION['username']=$name;
        $_SESSION['usertype']="admin";
        header("location:adminhome.php");
    }

    else
    {     

        $message= "username or password do not match";

        $_SESSION['loginMessage']=$message;

        header("location:login.php");
    }

}








?>
>>>>>>> 239de901e16da1817680c17ebf21a25d6c958bc9
