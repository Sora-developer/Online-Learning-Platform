<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 10px 0;
        }

        ul li a {
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
        }

        ul li a:hover {
            background: #0056b3;
        }

        .logout {
            margin-top: 20px;
            display: inline-block;
            background: red;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout:hover {
            background: darkred;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Student Dashboard</h2>
        <p>Welcome, <?php echo $user['name']; ?></p>
        <ul>
            <li><a href="enroll.php">Enroll in a Course</a></li>
            <li><a href="my_courses.php">My Courses</a></li>
            <li><a href="view_exams.php">View Exams</a></li>
            <li><a href="results.php">Exam Results</a></li>
            <li><a href="forum.php"> Discussion forum</a></li>
            <li><a href="certificate.php">Course Certificates</a></li>
            <!-- <li><a href="recommend.php">Recommended Courses</a></li> -->
        </ul>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>

</html>
