<?php
include 'db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] == 'student') {
                header('Location: student_dashboard.php');
            } else {
                header('Location: instructor_dashboard.php');
            }
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        text-align: center;
    }

    form {
        background: white;
        padding: 20px;
        margin: 50px auto;
        width: 300px;
        border-radius: 5px;
    }

    input,
    button {
        margin: 10px 0;
        padding: 8px;
        width: 100%;
    }

    button {
        background: #337ab7;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background: #286090;
    }
</style>

<form method="POST">
    Email: <input type="email" name="email" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="register.php">Register here</a></p>