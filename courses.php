<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'instructor') {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructor_id = $_SESSION['user']['id'];
    $price = $_POST['price'];

    $sql = "INSERT INTO courses (title, description, instructor_id, price) VALUES ('$title', '$description', '$instructor_id', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo "Course added successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<style>
    .container {
        width: 50%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 5px;
    }

    input,
    textarea,
    button {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
    }

    button {
        background: #008cba;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background: #005f73;
    }
</style>

<form method="POST" class="container">
    Title: <input type="text" name="title" required>
    Description: <textarea name="description" required></textarea>
    price: <input type="number" name="price" required>
    <button type="submit">Add Course</button>
</form>