<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header('Location: login.php');
    exit;
}

// Display an error message and stop execution
die('<div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif;">
        <h1>Page Under Construction</h1>
        <p>We are working hard to bring this page to you soon. Please check back later!</p>
    </div>');
