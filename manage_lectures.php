<?php
include 'db.php';
session_start();

// Ensure user is an instructor
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'instructor') {
    header('Location: login.php');
    exit;
}

// Get course ID from URL
if (!isset($_GET['course_id'])) {
    header('Location: update_course.php');
    exit;
}
$course_id = intval($_GET['course_id']);
$instructor_id = $_SESSION['user']['id'];

// Check if the instructor owns this course
$course_sql = "SELECT * FROM courses WHERE id = '$course_id' AND instructor_id = '$instructor_id'";
$course_result = $conn->query($course_sql);
if ($course_result->num_rows == 0) {
    die("You are not authorized to manage this course.");
}
$course = $course_result->fetch_assoc();

// Fetch course lectures
$lectures_sql = "SELECT * FROM lectures WHERE course_id = '$course_id'";
$lectures_result = $conn->query($lectures_sql);

// Update lecture details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_lecture'])) {
    $lecture_id = intval($_POST['lecture_id']);
    $lecture_title = $conn->real_escape_string($_POST['lecture_title']);
    $lecture_content = $conn->real_escape_string($_POST['lecture_content']);
    $video_url = $conn->real_escape_string($_POST['video_url']);

    $update_lecture_sql = "UPDATE lectures SET title = '$lecture_title', content = '$lecture_content', video_url = '$video_url' WHERE id = '$lecture_id' AND course_id = '$course_id'";
    $conn->query($update_lecture_sql);
    header("Location: manage_lectures.php?course_id=$course_id");
    exit;
}

// Add new lecture
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_lecture'])) {
    $new_lecture_title = $conn->real_escape_string($_POST['new_lecture_title']);
    $new_lecture_content = $conn->real_escape_string($_POST['new_lecture_content']);
    $new_video_url = $conn->real_escape_string($_POST['new_video_url']);

    $add_lecture_sql = "INSERT INTO lectures (course_id, title, content, video_url) VALUES ('$course_id', '$new_lecture_title', '$new_lecture_content', '$new_video_url')";
    $conn->query($add_lecture_sql);
    header("Location: manage_lectures.php?course_id=$course_id");
    exit;
}

// Delete lecture
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_lecture'])) {
    $lecture_id = intval($_POST['lecture_id']);
    $delete_lecture_sql = "DELETE FROM lectures WHERE id = '$lecture_id' AND course_id = '$course_id'";
    $conn->query($delete_lecture_sql);
    header("Location: manage_lectures.php?course_id=$course_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lectures</title>
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

        textarea,
        input {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .delete-button {
            background: #dc3545;
        }

        .delete-button:hover {
            background: #c82333;
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
    <a href="update_course.php" class="back-button">← Back to Courses</a>
    <a href="instructor_dashboard.php" class="back-button">← Back to Dashboard</a>

    <div class="container">
        <h2>Manage Lectures for: <?php echo htmlspecialchars($course['title']); ?></h2>

        <h3>Current Lectures</h3>
        <?php while ($lecture = $lectures_result->fetch_assoc()): ?>
            <div class="lecture">
                <form method="POST">
                    <input type="hidden" name="lecture_id" value="<?php echo $lecture['id']; ?>">

                    <label>Lecture Title:</label>
                    <input type="text" name="lecture_title" value="<?php echo htmlspecialchars($lecture['title']); ?>" required>

                    <label>Lecture Content:</label>
                    <textarea name="lecture_content" required><?php echo htmlspecialchars($lecture['content']); ?></textarea>

                    <label>Video URL:</label>
                    <input type="text" name="video_url" value="<?php echo htmlspecialchars($lecture['video_url']); ?>" required>

                    <button type="submit" name="update_lecture">Update Lecture</button>
                    <button type="submit" name="delete_lecture" class="delete-button" onclick="return confirm('Are you sure you want to delete this lecture?');">Delete Lecture</button>
                </form>
            </div>
        <?php endwhile; ?>

        <h3>Add New Lecture</h3>
        <form method="POST">
            <label>Lecture Title:</label>
            <input type="text" name="new_lecture_title" required>

            <label>Lecture Content:</label>
            <textarea name="new_lecture_content" required></textarea>

            <label>Video URL:</label>
            <input type="text" name="new_video_url" required>

            <button type="submit" name="add_lecture">Add Lecture</button>
        </form>
    </div>
</body>

</html>