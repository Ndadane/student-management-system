<?php


error_reporting(0);
session_start();
session_destroy();

if($_SESSION['message'])
{
    $message=$_SESSION['message'];

    echo "<script type='text/javascript'>
    
    alert('$message');
    
    </script>";
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" type="text/css" href="style.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    
    <nav>
        <label class="logo">Student Management System</label>

        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="login.php" class="btn btn-success">Login</a></li>
        </ul>
    </nav>

    <div class="section1">
        <label class= "img_text">Teaching students with passion</label>
        <img class="main_img" src="Classroom.jpg" alt="Student Management System"/>
    </div>

        <div class= "container">
            <div class= "row">
                <div class= "col-md-4">
                    <img class="welcome_img" src="school2.jpg"/>
                </div>
                <div class= "col-md-8">
                    <h1>Welcome to Student Management System</h1>
                    <p>Our Student Management System is designed to streamline the administrative tasks of educational institutions, making it easier for staff to manage student information, track academic progress, and communicate with students and parents. With an intuitive interface and robust features, our system aims to enhance the overall educational experience for everyone involved.</p>
                </div>
            </div>
        </div>
    </div>

<center>
    <h1>Our Teachers</h1>
</center>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img class="teacher_img" src="teacher1.jpg" alt="Teacher 1"/>
                <h3>Mr. John Doe</h3>
                <p>Mathematics Teacher with 10 years of experience.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img class="teacher_img" src="teacher2.jpg" alt="Teacher 2"/>
                <h3>Ms. Jane Smith</h3>
                <p>Mathematics & Science Teacher focused on excellence.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img class="teacher_img" src="teacher3.jpg" alt="Teacher 3"/>
                <h3>Mrs. Emily Johnson</h3>
                <p>Science Teacher passionate about experiments..</p>
            </div>
        </div>
    </div>
</div>

<center>
    <h1>Our Courses</h1>
</center>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img class="teacher_img" src="course1.jpg" alt="Teacher 1"/>
                <h3>Web Development</h3>
                <p>Learn web development from scratch to advanced level.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img class="teacher_img" src="course2.jpg" alt="Teacher 2"/>
                <h3>Full Stack Development</h3>
                <p>Learn full stack development from scratch to advanced level.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img class="teacher_img" src="course3.jpg" alt="Teacher 3"/>
                <h3>Mathematics</h3>
                <p>Learn mathematics from scratch to advanced level.</p>
            </div>
        </div>
    </div>
</div>

<center>
    <h1 class="adm">Admission Form</h1>
</center>

<div align="center" class="admission_form">
    <form action="data_check.php" method="POST">
        <div class="adm_int">
            <label class="label_text">Name:</label>
            <input class="input_deg" type="text" name="name" >
        </div>

        <div class="adm_int">
            <label class="label_text">Email:</label>
            <input class="input_deg" type="text" name="email" >
        </div>

        <div class="adm_int">
            <label class="label_text">Phone:</label>
            <input class="input_deg" type="text" name="phone" >
        </div>

        <div class="adm_int">
            <label class="label_text">Message:</label>
            <textarea class="input_txt" name="message"></textarea>
        </div>    

        <div class="adm_int">
            <input class="btn btn-primary" id="submit" type="submit" value="apply" name="apply">
        </div>
    </form> 

    <footer>
        <h2 class="footer_text">All @copyrights reserved by Gigaba Tech</h2>
    </footer>

</body>
</html>

