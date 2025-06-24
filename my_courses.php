<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}

$student_id = $_SESSION['user']['id'];

// Fetch enrolled courses
$enrolled_sql = "SELECT DISTINCT courses.* FROM courses 
                 INNER JOIN enrollments ON courses.id = enrollments.course_id 
                 WHERE enrollments.student_id = '$student_id'";
$enrolled_result = $conn->query($enrolled_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            color: #343a40;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            font-size: 18px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .no-courses {
            font-size: 18px;
            color: #6c757d;
        }

        .dashboard-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }

        .dashboard-button:hover {
            background: #218838;
        }
    </style>
</head>

<body>
    <h2>My Courses</h2>
    <div class="container">
        <?php if ($enrolled_result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $enrolled_result->fetch_assoc()): ?>
                    <li>
                        <a href="course_content.php?course_id=<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="no-courses">You have not enrolled in any courses yet.</p>
        <?php endif; ?>

        <a href="student_dashboard.php" class="dashboard-button">← Back to Dashboard</a>
    </div>
</body>

</html>