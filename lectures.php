<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$course_id = $_GET['course_id'];
$sql = "SELECT * FROM lectures WHERE course_id = '$course_id'";
$result = $conn->query($sql);
?>

<style>
    .lecture-container {
        width: 70%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 5px;
    }

    video {
        width: 100%;
        margin: 10px 0;
    }
</style>

<div class="lecture-container">
    <h2>Lectures</h2>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <h3><?php echo $row['title']; ?></h3>
        <video controls>
            <source src="<?php echo $row['video_url']; ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php } ?>
</div>