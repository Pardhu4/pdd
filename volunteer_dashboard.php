<?php
// Database connection settings
$servername = "localhost";  // Change if different
$username = "root";          // Your database username
$password = "";              // Your database password
$dbname = "surplus_to_serve";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume volunteer ID is passed through session or URL for demonstration
$volunteer_id = 1; // Replace this with dynamic value based on logged-in user

// Fetch badges for the specific volunteer
$sql = "SELECT badge_name FROM badges WHERE volunteer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$badges_result = $stmt->get_result();

// Fetch volunteer stats (mock query, replace with actual logic)
$stats = [
    'hours_volunteered' => 50,  // Replace with dynamic query if applicable
    'tasks_completed' => 10     // Replace with dynamic query if applicable
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
    <style>
        /* (Existing styles retained) */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: url('volunteer_background.jpg') no-repeat center center fixed;
            background-size: cover;
            background-color: #f4f4f9;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 10px;
        }
        .container {
            margin: 0 auto;
            max-width: 800px;
            padding-top: 50px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .badges-list {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 10px;
        }
        .badge {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .badge:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2), transparent);
            opacity: 0.7;
            pointer-events: none;
        }
        .badge span {
            z-index: 1;
        }
        .no-data {
            color: #888;
            font-style: italic;
            text-align: center;
        }
        .badge {
            animation: pulse 2s infinite ease-in-out;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(42, 42, 42, 0.9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar .logo .logo-image {
            width: 40px;
            height: auto;
            border-radius: 50%;
        }
        .navbar .nav-links {
            display: flex;
            gap: 20px;
            padding-right: 25px;
        }
        .navbar .nav-links button {
            background: none;
            border: none;
            color: white;
            font-size: 1em;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .navbar .nav-links button img {
            width: 20px;
            height: 20px;
        }
        .navbar .nav-links button:hover {
            background-color: #555;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
        <button onclick="goBack()" style="background-color: #ff4d6d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Go Back</button>

            <img src="website_logo.png" alt="Logo" class="logo-image">
            <h2>Volunteer Dashboard</h2>
        </div>
        <div class="nav-links">
            <button onclick="location.href='about_us.php'">
                <img src="about_us_icon.png" alt="About Us Icon">
            </button>
            <button onclick="location.href='view_alerts.php'">
                <img src="alert.png" alt="Alert Icon">
            </button>
            <button onclick="location.href='notifications.php'">
                <img src="bell_icon.png" alt="Notifications Icon">
            </button>
            <button onclick="location.href='volunteer_resources.html'">
                <img src="book.png" alt="Resources Icon">
            </button>
            <button onclick="location.href='vol_admin_dashboard.php'">
                <img src="task.png" alt="Profile Icon">
            </button>
        </div>
    </div>

    <div class="container">
        <!-- Badges Section -->
        <div class="section">
            <h2>Badges Earned</h2>
            <?php if ($badges_result->num_rows > 0): ?>
                <div class="badges-list">
                    <?php while ($badge = $badges_result->fetch_assoc()): ?>
                        <div class="badge">
                            <span><?php echo htmlspecialchars($badge['badge_name']); ?></span>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-data">No badges earned yet.</p>
            <?php endif; ?>
        </div>

        <!-- Volunteer Stats Section -->
        <div class="section">
            <h2>Your Stats</h2>
            <p><strong>Hours Volunteered:</strong> <?php echo htmlspecialchars($stats['hours_volunteered'] ?? 0); ?></p>
            <p><strong>Tasks Completed:</strong> <?php echo htmlspecialchars($stats['tasks_completed'] ?? 0); ?></p>
        </div>
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

<?php
// Close database connection
$conn->close();
?>