<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user']['id'];
    $exam_id = $_POST['exam_id'];
    $score = $_POST['score'];

    // Insert new attempt
    $sql = "INSERT INTO exam_attempts (user_id, exam_id, score) VALUES ('$user_id', '$exam_id', '$score')";
    $conn->query($sql);

    // Update best score
    $sql = "SELECT MAX(score) AS best_score FROM exam_attempts WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
    $result = $conn->query($sql);
    $best_score = $result->fetch_assoc()['best_score'];

    $sql = "UPDATE exam_results SET best_score = '$best_score' WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
    $conn->query($sql);
}
?>

<style>
    .exam-container {
        width: 60%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 5px;
    }
</style>

<div class="exam-container">
    <h2>Exam Attempts</h2>
    <form method="POST">
        <input type="hidden" name="exam_id" value="<?php echo $_GET['exam_id']; ?>">
        <label>Enter Score:</label>
        <input type="number" name="score" required>
        <button type="submit">Submit Score</button>
    </form>
</div>