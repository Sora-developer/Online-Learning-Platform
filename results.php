<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user']['id'];

// Get student's best scores for each exam
$sql = "SELECT e.exam_title, r.best_score
        FROM exam_results r
        JOIN exams e ON r.exam_id = e.id
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>My Exam Results</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      padding: 20px;
    }

    .results-container {
      width: 70%;
      margin: auto;
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table,
    th,
    td {
      border: 1px solid #ddd;
    }

    th,
    td {
      padding: 12px;
      text-align: center;
    }

    th {
      background-color: #0072ff;
      color: white;
    }
  </style>
</head>

<body>
  <div class="results-container">
    <h2>My Exam Results</h2>
    <table>
      <tr>
        <th>Exam</th>
        <th>Best Score</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['best_score']); ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>

</html>