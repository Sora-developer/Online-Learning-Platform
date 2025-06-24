<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['course_id'])) {
    header('Location: my_courses.php');
    exit;
}

$course_id = intval($_GET['course_id']);

// Fetch course details
$course_sql = "SELECT * FROM courses WHERE id = '$course_id'";
$course_result = $conn->query($course_sql);
$course = $course_result->fetch_assoc();

if (!$course) {
    echo "<h2>Course not found</h2>";
    exit;
}

// Fetch lectures for the course
$lectures_sql = "SELECT id, title, content, video_url, created_at FROM lectures WHERE course_id = '$course_id' ORDER BY created_at ASC";
$lectures_result = $conn->query($lectures_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Lectures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .container {
            width: 60%;
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

        .lecture {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f1f1f1;
            text-align: left;
        }

        .lecture h3 {
            margin: 0 0 10px;
        }

        .video-container {
            width: 100%;
            margin-top: 10px;
        }

        iframe {
            width: 100%;
            height: 315px;
            border-radius: 10px;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        .no-content {
            font-size: 18px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <h2><?php echo htmlspecialchars($course['title']); ?> - Course Lectures</h2>
    <div class="container">
        <?php if ($lectures_result->num_rows > 0): ?>
            <?php while ($lecture = $lectures_result->fetch_assoc()): ?>
                <div class="lecture">
                    <h3><?php echo htmlspecialchars($lecture['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($lecture['content'])); ?></p>
                    <?php if (!empty($lecture['video_url'])): ?>
                        <div class="video-container">
                            <iframe src="<?php echo htmlspecialchars($lecture['video_url']); ?>" allowfullscreen></iframe>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-content">No lectures available for this course.</p>
        <?php endif; ?>
        <a href="my_courses.php" class="back-btn">Back to My Courses</a>
    </div>
</body>

</html>