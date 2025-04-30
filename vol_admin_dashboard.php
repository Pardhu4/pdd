<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "surplus_to_serve";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$volunteer_id = null;

// Check if volunteer ID is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['volunteer_id'])) {
    $volunteer_id = intval($_POST['volunteer_id']);
}

// Display form if volunteer ID is not set
if ($volunteer_id === null) {
    echo '
    <form method="POST" style="text-align: center; margin: 50px;">
        <label for="volunteer_id" style="font-size: 1.5em; color: #333;">Enter Volunteer ID:</label><br><br>
        <input type="text" name="volunteer_id" id="volunteer_id" required style="padding: 12px; width: 200px; border: 2px solid #007BFF; border-radius: 8px; font-size: 1em;">
        <button type="submit" style="padding: 12px 20px; background-color: #007BFF; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1em; margin-left: 10px;">Submit</button>
    </form>
    ';
    exit();  // Stop script execution until volunteer ID is entered
}

// Handle task acceptance or completion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
    
    if (isset($_POST['accept_task'])) {
        $update_sql = "UPDATE tasks SET status = 'accepted', accepted_by = ? WHERE task_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $volunteer_id, $task_id);
        $stmt->execute();
        echo "<script>alert('Task accepted successfully!'); window.location.href='vol_admin_dashboard.php';</script>";
        exit();
    } elseif (isset($_POST['complete_task'])) {
        $update_sql = "UPDATE tasks SET status = 'completed' WHERE task_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        echo "<script>alert('Task completed successfully!'); window.location.href='vol_admin_dashboard.php';</script>";
        exit();
    }
}

// Fetch tasks assigned to the logged-in volunteer
$sql = "SELECT task_id, task_name, task_description, status FROM tasks 
        WHERE assigned_to = ? AND (status = 'available' OR status = 'accepted')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #e2f3f5, #cfd9df);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #f4f4f4;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        button {
            padding: 8px 12px;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #28a745;
        }
        .complete-btn {
            background-color: #007BFF;
        }
        .accept-btn:hover {
            background-color: #218838;
        }
        .complete-btn:hover {
            background-color: #0056b3;
        }
        form input[type="hidden"] {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
    <button onclick="goBack()" style="background-color: #ff4d6d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Go Back</button>

        <h2>Volunteer Dashboard</h2>
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['task_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['task_description']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($row['status'] === 'available'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
                                            <button class="accept-btn" type="submit" name="accept_task">Accept</button>
                                            <input type="hidden" name="volunteer_id" value="<?php echo $volunteer_id; ?>">
                                        </form>
                                    <?php elseif ($row['status'] === 'accepted'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
                                            <button class="complete-btn" type="submit" name="complete_task">Complete</button>
                                            <input type="hidden" name="volunteer_id" value="<?php echo $volunteer_id; ?>">
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No tasks assigned.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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