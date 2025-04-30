<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$dbname = "surplus_to_serve";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch alerts from the database
$sql = "SELECT * FROM alerts ORDER BY created_at DESC";
$result = $conn->query($sql);

$alerts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alerts[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Alerts</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .alert-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .alert-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .alert-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .alert-message {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }

        .alert-footer {
            font-size: 14px;
            color: #888;
            text-align: right;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Alerts</h1>
        <?php if (count($alerts) > 0): ?>
            <?php foreach ($alerts as $alert): ?>
                <div class="alert-card">
                    <div class="alert-title"><?php echo htmlspecialchars($alert['title']); ?></div>
                    <div class="alert-message"><?php echo nl2br(htmlspecialchars($alert['message'])); ?></div>
                    <div class="alert-footer"><?php echo date("F j, Y, g:i a", strtotime($alert['created_at'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No alerts found.</p>
        <?php endif; ?>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const alertCards = document.querySelectorAll(".alert-card");
            alertCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.animation = "fadeIn 0.5s ease-in-out";
            });
        });
    </script>
</body>
</html>