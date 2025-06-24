<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}

$student_id = $_SESSION['user']['id'];

// Fetch courses not enrolled by the student
$sql = "SELECT * FROM courses WHERE id NOT IN (SELECT course_id FROM enrollments WHERE student_id = '$student_id')";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $amount = $_POST['amount'];

    $sql = "INSERT INTO enrollments (student_id, course_id, amount, payment_status) VALUES ('$student_id', '$course_id', '$amount', 'paid')";
    if ($conn->query($sql) === TRUE) {
        echo "Enrollment successful";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        text-align: center;
    }

    .enroll-container {
        width: 50%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #343a40;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
    }

    select,
    input,
    button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ced4da;
        border-radius: 5px;
    }

    button {
        background: #28a745;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }

    button:hover {
        background: #218838;
    }

    p {
        font-size: 18px;
        color: #6c757d;
    }
</style>

<h2>Available Courses</h2>
<div class="enroll-container">
    <?php if ($result->num_rows > 0): ?>
        <form method="POST">
            <label for="course_id">Select Course:</label>
            <select name="course_id" required>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['title'] . " - $" . $row['price']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="amount">Amount:</label>
            <input type="number" name="amount" required>
            <button type="submit">Enroll & Pay</button>
        </form>
    <?php else: ?>
        <p>No available courses to enroll.</p>
    <?php endif; ?>
</div>