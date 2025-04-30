<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'surplus_to_serve';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle stats update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stats'])) {
    $volunteer_id = $_POST['volunteer_id'];
    $hours_volunteered = $_POST['hours_volunteered'];
    $tasks_completed = $_POST['tasks_completed'];
    
    $update_stats_query = "UPDATE volunteer_stats SET hours_volunteered=$hours_volunteered, tasks_completed=$tasks_completed WHERE volunteer_id = $volunteer_id";
    
    if ($conn->query($update_stats_query)) {
        $message = "Stats updated successfully for Volunteer ID: $volunteer_id!";
    } else {
        $message = "Error updating stats.";
    }
}

// Handle badge addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_badge'])) {
    $volunteer_id = $_POST['volunteer_id'];
    $badge_name = $_POST['badge_name'];
    
    $insert_badge_query = "INSERT INTO badges (volunteer_id, badge_name) VALUES ($volunteer_id, '$badge_name')";
    
    if ($conn->query($insert_badge_query)) {
        $message = "Badge added successfully for Volunteer ID: $volunteer_id!";
    } else {
        $message = "Error adding badge.";
    }
}

// Handle badge deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_badge'])) {
    $badge_id = $_POST['badge_id'];
    
    $delete_badge_query = "DELETE FROM badges WHERE id = $badge_id";
    
    if ($conn->query($delete_badge_query)) {
        $message = "Badge deleted successfully!";
    } else {
        $message = "Error deleting badge.";
    }
}

// Fetch all badges for display
$badges_query = "SELECT * FROM badges";
$badges_result = $conn->query($badges_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Volunteer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        .container {
            margin: 0 auto;
            max-width: 800px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            padding-top: 60px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #ff4d6d;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2a2a2a;
        }
        .message {
            text-align: center;
            font-weight: bold;
            color: green;
            margin-bottom: 20px;
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
            padding: 8px 15px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .navbar .logo {
            font-size: 1.5em;
            font-weight: bold;
        }
        .navbar .nav-links {
            display: flex;
            gap: 20px;
            padding-right: 40px;
        }
        .navbar .nav-links button {
            background: none;
            border: none;
            color: white;
            font-size: 1em;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .navbar .nav-links button:hover {
            background-color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo">SurplusToServe</div>
    <h1>Edit Volunteer Dashboard</h1>
    <div class="nav-links">
        <button onclick="location.href='admin.php'">Back to Admin Panel</button>
        <button onclick="location.href='about_us.html'">About Us</button>
        <button onclick="location.href='volunteer_resources.html'">Resources</button>
    </div>
</div>
   
<div class="container">
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Edit Stats Section -->
    <div class="section">
        <h2>Edit Stats</h2>
        <form action="edit_volunteer_dashboard.php" method="POST">
            <label for="volunteer_id">Volunteer ID</label>
            <input type="number" id="volunteer_id" name="volunteer_id" required>

            <label for="hours_volunteered">Hours Volunteered</label>
            <input type="number" id="hours_volunteered" name="hours_volunteered" required>

            <label for="tasks_completed">Tasks Completed</label>
            <input type="number" id="tasks_completed" name="tasks_completed" required>

            <button type="submit" name="update_stats">Update Stats</button>
        </form>
    </div>

    <!-- Edit Badges Section -->
    <div class="section">
        <h2>Manage Badges</h2>
        <form action="edit_volunteer_dashboard.php" method="POST">
            <label for="volunteer_id">Volunteer ID</label>
            <input type="number" id="volunteer_id" name="volunteer_id" required>

            <label for="badge_name">Badge Name</label>
            <input type="text" id="badge_name" name="badge_name" required>

            <button type="submit" name="add_badge">Add Badge</button>
        </form>

        <!-- Display and Delete Badges Table -->
        <?php if ($badges_result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Volunteer ID</th>
                    <th>Badge Name</th>
                    <th>Action</th>
                </tr>
                <?php while ($badge = $badges_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($badge['id']); ?></td>
                        <td><?php echo htmlspecialchars($badge['volunteer_id']); ?></td>
                        <td><?php echo htmlspecialchars($badge['badge_name']); ?></td>
                        <td>
                            <form action="edit_volunteer_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="badge_id" value="<?php echo $badge['id']; ?>">
                                <button type="submit" name="delete_badge" onclick="return confirm('Are you sure you want to delete this badge?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No badges found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>