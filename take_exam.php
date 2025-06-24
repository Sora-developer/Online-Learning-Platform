<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['exam_id'])) {
    die("Invalid access. Exam ID is missing.");
}

$exam_id = intval($_GET['exam_id']);
$student_id = $_SESSION['user']['id'];

// Fetch exam details
$exam_query = "SELECT course_id FROM exams WHERE id = ?";
$stmt = $conn->prepare($exam_query);
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Exam not found.");
}
$exam = $result->fetch_assoc();
$course_id = $exam['course_id'];

// Fetch questions from exam_questions table
$questions_query = "SELECT id, question, option1, option2, option3, option4, correct_answer FROM exam_questions WHERE exam_id = ?";
$stmt = $conn->prepare($questions_query);
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$questions_result = $stmt->get_result();

$questions = [];
while ($row = $questions_result->fetch_assoc()) {
    $questions[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    $total_questions = count($questions);

    foreach ($questions as $question) {
        $question_id = $question['id'];
        if (isset($_POST["answer_$question_id"])) {
            $user_answer = trim(strtolower($_POST["answer_$question_id"]));
            $correct_answer = trim(strtolower($question['correct_answer']));



            if ($user_answer === $correct_answer) {
                $score++;
            }
        }
    }

    $final_score = ($total_questions > 0) ? round(($score / $total_questions) * 100) : 0;

    // Insert attempt into exam_attempts table
    $insert_attempt = "INSERT INTO exam_attempts (student_id, exam_id, course_id, score) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_attempt);
    $stmt->bind_param("iiii", $student_id, $exam_id, $course_id, $final_score);
    if ($stmt->execute()) {
        echo "<script>alert('Exam submitted successfully! Your score: $final_score%'); window.location.href='view_exams.php';</script>";
    } else {
        die("Error submitting exam: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Exam</title>
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

        .question {
            font-weight: bold;
            margin-top: 10px;
        }

        .options {
            text-align: left;
            margin-left: 20px;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        button:hover {
            background: #218838;
        }
    </style>
</head>

<body>

    <div class="exam-container">
        <h2>Take Exam</h2>
        <form method="POST">
            <?php foreach ($questions as $row) { ?>
                <div class="question"><?php echo htmlspecialchars($row['question']); ?></div>
                <div class="options">
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="1"> <?php echo htmlspecialchars($row['option1']); ?><br>
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="2"> <?php echo htmlspecialchars($row['option2']); ?><br>
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="3"> <?php echo htmlspecialchars($row['option3']); ?><br>
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="4"> <?php echo htmlspecialchars($row['option4']); ?><br>
                </div>
            <?php } ?>
            <button type="submit">Submit Exam</button>
        </form>
    </div>

</body>

</html>