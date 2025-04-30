<?php
// Database connection
include 'connection.php';

// Add a new notification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_notification'])) {
    $title = $mysqli->real_escape_string($_POST['title']);
    $message = $mysqli->real_escape_string($_POST['message']);

    $sql = "INSERT INTO notifications (title, message) VALUES ('$title', '$message')";
    $mysqli->query($sql);
}

// Delete a notification
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM notifications WHERE id = $id");
}

// Fetch all notifications
$result = $mysqli->query("SELECT * FROM notifications ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        form input, form textarea, form button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .action-buttons a {
            margin-right: 10px;
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .edit {
            background-color: #007BFF;
        }
        .delete {
            background-color: #DC3545;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #ff4d6d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        form button{
            background-color: #ff4d6d;
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #ff4d6d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <a href="admin.php" class="back-button">Back to Admin Panel</a>

    <h1>Manage Notifications</h1>

    <!-- Add Notification Form -->
    <form method="POST">
        <h2>Add Notification</h2>
        <input type="text" name="title" placeholder="Notification Title" required>
        <textarea name="message" placeholder="Notification Message" rows="5" required></textarea>
        <button type="submit" name="add_notification">Add Notification</button>
    </form>

    <!-- Notifications Table -->
    <h2>All Notifications</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['message']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td class="action-buttons">
                        <a href="edit_notification.php?id=<?= $row['id'] ?>" class="edit">Edit</a>
                        <a href="?delete=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>