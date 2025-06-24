<?php
include 'db.php';
session_start();

// Ensure user is an instructor
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'instructor') {
    header('Location: login.php');
    exit;
}

$instructor_id = $_SESSION['user']['id'];

// Fetch courses created by the instructor
$courses_sql = "SELECT * FROM courses WHERE instructor_id = '$instructor_id'";
$courses_result = $conn->query($courses_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .container {
            width: 70%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .course-list {
            list-style: none;
            padding: 0;
        }

        .course-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .course-list a {
            text-decoration: none;
            font-size: 18px;
            color: #007bff;
        }

        .back-button {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .back-button:hover {
            background: #218838;
        }
    </style>
</head>

<body>
    <a href="instructor_dashboard.php" class="back-button">← Back to Dashboard</a>

    <div class="container">
        <h2>Your Courses</h2>
        <ul class="course-list">
            <?php while ($course = $courses_result->fetch_assoc()): ?>
                <li>
                    <a href="manage_lectures.php?course_id=<?php echo $course['id']; ?>">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>

</html>