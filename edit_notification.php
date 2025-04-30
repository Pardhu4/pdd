<?php
// Database connection
include 'connection.php';

// Fetch the notification by ID
$id = intval($_GET['id']);
$result = $mysqli->query("SELECT * FROM notifications WHERE id = $id");
$notification = $result->fetch_assoc();

// Update the notification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $mysqli->real_escape_string($_POST['title']);
    $message = $mysqli->real_escape_string($_POST['message']);

    $sql = "UPDATE notifications SET title = '$title', message = '$message' WHERE id = $id";
    $mysqli->query($sql);

    header("Location: admin_notifications.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form input, form textarea, form button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Edit Notification</h1>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($notification['title']) ?>" required>
        <textarea name="message" rows="5" required><?= htmlspecialchars($notification['message']) ?></textarea>
        <button type="submit">Update Notification</button>
    </form>
</body>
</html>