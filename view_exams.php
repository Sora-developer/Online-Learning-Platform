<?php
include 'db.php';
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}

$student_id = $_SESSION['user']['id'];

// Fetch courses the student is enrolled in
$courses_sql = "SELECT courses.id, courses.title FROM enrollments 
                JOIN courses ON enrollments.course_id = courses.id 
                WHERE enrollments.student_id = '$student_id'";
$courses_result = $conn->query($courses_sql);

$exams = [];

// Fetch distinct exams for enrolled courses
$exam_sql = "SELECT DISTINCT exams.id, exams.exam_title, courses.title AS course_title 
             FROM exams 
             JOIN courses ON exams.course_id = courses.id
             JOIN enrollments ON courses.id = enrollments.course_id
             WHERE enrollments.student_id = '$student_id'";

$exam_result = $conn->query($exam_sql);

if ($exam_result->num_rows > 0) {
    while ($exam = $exam_result->fetch_assoc()) {
        $exams[] = $exam;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Exams</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .exam-container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background: #218838;
        }

        .dashboard-button {
            background-color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="exam-container">
        <h2>Available Exams</h2>

        <?php if (count($exams) > 0): ?>
            <table>
                <tr>
                    <th>Course</th>
                    <th>Exam Title</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($exam['course_title']); ?></td>
                        <td><?php echo htmlspecialchars($exam['exam_title']); ?></td>
                        <td>
                            <button onclick="location.href='take_exam.php?exam_id=<?php echo $exam['id']; ?>'">
                                Start Exam
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No exams available for your enrolled courses.</p>
        <?php endif; ?>

        <button class="dashboard-button" onclick="window.location.href='student_dashboard.php'">
            Back to Dashboard
        </button>
    </div>

</body>

</html>