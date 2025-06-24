<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user']['id'];
$sql = "SELECT course_id FROM enrollments WHERE student_id = '$user_id'";
$result = $conn->query($sql);
$enrolled_courses = [];
while ($row = $result->fetch_assoc()) {
    $enrolled_courses[] = $row['course_id'];
}
if (!empty($enrolled_courses)) {
    $course_list = implode(",", $enrolled_courses);
    $sql = "SELECT * FROM courses WHERE id NOT IN ($course_list) ORDER BY RAND() LIMIT 5";
} else {
    $sql = "SELECT * FROM courses ORDER BY RAND() LIMIT 5";
}
$recommendations = $conn->query($sql);
?>

<style>
    .recommend-container {
        width: 60%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 5px;
    }
</style>

<div class="recommend-container">
    <h2>Recommended Courses</h2>
    <ul>
        <?php while ($row = $recommendations->fetch_assoc()) { ?>
            <li><a href="course_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></li>
        <?php } ?>
    </ul>
</div>