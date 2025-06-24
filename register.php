<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
    } else {
        echo "Error: " . $conn->error;
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
    select,
    button {
        margin: 10px 0;
        padding: 8px;
        width: 100%;
    }

    button {
        background: #5cb85c;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background: #4cae4c;
    }

    .login-link {
        display: block;
        margin-top: 10px;
        color: #007bff;
        text-decoration: none;
    }

    .login-link:hover {
        text-decoration: underline;
    }
</style>

<form method="POST">
    Name: <input type="text" name="name" required>
    Email: <input type="email" name="email" required>
    Password: <input type="password" name="password" required>
    Role: <select name="role">
        <option value="student">Student</option>
        <option value="instructor">Instructor</option>
    </select>
    <button type="submit">Register</button>
    <a class="login-link" href="login.php">Already have an account? Login here</a>
</form>