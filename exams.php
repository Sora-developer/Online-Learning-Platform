<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'instructor') {
    header('Location: login.php');
    exit;
}

$instructor_id = $_SESSION['user']['id'];

// Fetch courses created by the instructor
$courses_sql = "SELECT id, title FROM courses WHERE instructor_id = '$instructor_id'";
$courses_result = $conn->query($courses_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $exam_title = $conn->real_escape_string($_POST['exam_title']);

    // Insert new exam
    $sql = "INSERT INTO exams (course_id, exam_title) VALUES ('$course_id', '$exam_title')";
    if ($conn->query($sql) === TRUE) {
        $exam_id = $conn->insert_id;

        foreach ($_POST['questions'] as $index => $question) {
            $question = $conn->real_escape_string($question);
            $option1 = $conn->real_escape_string($_POST['option1'][$index]);
            $option2 = $conn->real_escape_string($_POST['option2'][$index]);
            $option3 = $conn->real_escape_string($_POST['option3'][$index]);
            $option4 = $conn->real_escape_string($_POST['option4'][$index]);
            $correct_option = $conn->real_escape_string($_POST['correct_option'][$index]);

            $sql = "INSERT INTO exam_questions (exam_id, question, option1, option2, option3, option4, correct_answer) 
                    VALUES ('$exam_id', '$question', '$option1', '$option2', '$option3', '$option4', '$correct_option')";
            $conn->query($sql);
        }
        echo "Exam and questions added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Exam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .exam-container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        select,
        input,
        textarea,
        button {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .question-block {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            background: #f1f1f1;
        }
    </style>
</head>

<body>

    <form method="POST" class="exam-container">
        <h2>Create New Exam</h2>

        <label>Select Course:</label>
        <select name="course_id" required>
            <option value="">-- Select Course --</option>
            <?php while ($row = $courses_result->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Exam Title:</label>
        <input type="text" name="exam_title" required>

        <h3>Exam Questions</h3>
        <div id="questions">
            <div class="question-block">
                <label>Question:</label>
                <input type="text" name="questions[]" required>

                <label>Option 1:</label>
                <input type="text" name="option1[]" required>

                <label>Option 2:</label>
                <input type="text" name="option2[]" required>

                <label>Option 3:</label>
                <input type="text" name="option3[]" required>

                <label>Option 4:</label>
                <input type="text" name="option4[]" required>

                <label>Correct Option:</label>
                <select name="correct_option[]" required>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            </div>
        </div>

        <button type="button" onclick="addQuestion()">Add Another Question</button>
        <button type="submit">Submit Exam</button>

        <!-- Back to Dashboard Button -->
        <button type="button" onclick="window.location.href='instructor_dashboard.php'" style="background-color: #6c757d;">
            Back to Dashboard
        </button>
    </form>

    <script>
        function addQuestion() {
            let container = document.getElementById('questions');
            let newQuestion = document.createElement('div');
            newQuestion.classList.add('question-block');
            newQuestion.innerHTML = `
            <label>Question:</label> <input type="text" name="questions[]" required>

            <label>Option 1:</label> <input type="text" name="option1[]" required>
            <label>Option 2:</label> <input type="text" name="option2[]" required>
            <label>Option 3:</label> <input type="text" name="option3[]" required>
            <label>Option 4:</label> <input type="text" name="option4[]" required>

            <label>Correct Option:</label>
            <select name="correct_option[]" required>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
            </select>
        `;
            container.appendChild(newQuestion);
        }
    </script>

</body>

</html>