<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Handling new message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $sql = "INSERT INTO forum (user_id, message) VALUES ('$user_id', '$message')";
    $conn->query($sql);
}

// Handling new comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment']) && isset($_POST['message_id'])) {
    $message_id = intval($_POST['message_id']);
    $comment = $conn->real_escape_string($_POST['comment']);
    $sql = "INSERT INTO comments (message_id, user_id, comment) VALUES ('$message_id', '$user_id', '$comment')";
    $conn->query($sql);
}

// Fetch all forum messages
$messages_sql = "SELECT forum.*, users.name FROM forum 
                 JOIN users ON forum.user_id = users.id 
                 ORDER BY forum.created_at DESC";
$messages_result = $conn->query($messages_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Forum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .forum-container {
            width: 70%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            text-align: left;
        }

        .comment-section {
            margin-left: 20px;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 5px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .comment {
            padding: 5px 0;
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="forum-container">
        <h2>Discussion Forum</h2>
        <form method="POST">
            <textarea name="message" placeholder="Post a message..." required></textarea>
            <button type="submit">Post Message</button>
        </form>

        <h3>All Messages</h3>
        <?php while ($message = $messages_result->fetch_assoc()) : ?>
            <div class="message">
                <strong><?php echo htmlspecialchars($message['name']); ?>:</strong>
                <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>

                <!-- Fetch comments for this message -->
                <?php
                $message_id = $message['id'];
                $comments_sql = "SELECT comments.*, users.name FROM comments 
                                 JOIN users ON comments.user_id = users.id 
                                 WHERE comments.message_id = '$message_id' 
                                 ORDER BY comments.created_at ASC";
                $comments_result = $conn->query($comments_sql);
                ?>

                <div class="comment-section">
                    <h4>Comments</h4>
                    <?php while ($comment = $comments_result->fetch_assoc()) : ?>
                        <div class="comment">
                            <strong><?php echo htmlspecialchars($comment['name']); ?>:</strong>
                            <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                        </div>
                    <?php endwhile; ?>

                    <form method="POST">
                        <textarea name="comment" placeholder="Add a comment..." required></textarea>
                        <input type="hidden" name="message_id" value="<?php echo $message_id; ?>">
                        <button type="submit">Comment</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>