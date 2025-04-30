<?php
// Database connection
include 'connection.php';

// Fetch all notifications
$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .notification {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .notification h3 {
            margin: 0 0 10px;
            color: #333;
        }
        .notification p {
            margin: 0 0 5px;
        }
        .notification time {
            font-size: 0.8em;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
    <button onclick="goBack()" style="background-color: #ff4d6d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Go Back</button>

        <h1>Notifications</h1>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="notification">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><?= htmlspecialchars($row['message']) ?></p>
                <time><?= htmlspecialchars($row['created_at']) ?></time>
            </div>
        <?php endwhile; ?>
    </div>
</body>
<script> 
    function goBack() {
    if (document.referrer !== "") {
        window.history.back();
    } else {
        window.location.href = "your-default-url.html"; // Replace with your fallback URL
    }
}

</script>
</html>