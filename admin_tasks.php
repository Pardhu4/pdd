<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "surplus_to_serve";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle task creation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_task'])) {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $assigned_to = !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : NULL;

    $sql = "INSERT INTO tasks (task_name, task_description, assigned_to, status) VALUES (?, ?, ?, 'available')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $task_name, $task_description, $assigned_to);
    $stmt->execute();
    
    echo "<script>alert('New task has been created successfully!'); window.location.href='admin_tasks.php';</script>";
    exit();
}

// Handle task deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_task'])) {
    $task_id = intval($_POST['task_id']);
    $delete_sql = "DELETE FROM tasks WHERE task_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    
    echo "<script>alert('Task deleted successfully!'); window.location.href='admin_tasks.php';</script>";
    exit();
}

// Fetch tasks with their statuses
$sql = "SELECT 
            t.task_id, 
            t.task_name, 
            t.task_description, 
            t.status, 
            v.name AS assigned_to 
        FROM tasks t 
        LEFT JOIN volunteers v 
        ON t.assigned_to = v.volunteer_id";
$result = $conn->query($sql);

// Fetch volunteers for assignment dropdown
$volunteers_sql = "SELECT volunteer_id, name FROM volunteers";
$volunteers_result = $conn->query($volunteers_sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f9f9f9, #d1e8e2);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #ff4d6d;
        }
        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, textarea, select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #ff4d6d;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #2a2a2a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        .edit-btn {
            background-color: #FFC107;
        }
        .delete-btn {
            background-color: #DC3545;
        }
        .edit-btn:hover {
            background-color: #e0a800;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Task Management</h2>

        <!-- Create Task Form -->
        <form method="POST" action="admin_tasks.php">
            <h3>Create a New Task</h3>
            <input type="text" name="task_name" placeholder="Task Name" required>
            <textarea name="task_description" placeholder="Task Description" rows="4" required></textarea>
            <select name="assigned_to">
                <option value="">Assign to Volunteer (Optional)</option>
                <?php while ($volunteer = $volunteers_result->fetch_assoc()): ?>
                    <option value="<?php echo $volunteer['volunteer_id']; ?>">
                        <?php echo htmlspecialchars($volunteer['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="create_task">Create Task</button>
        </form>

        <!-- Display Existing Tasks -->
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Assigned To</th>
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
                            <td><?php echo htmlspecialchars($row['assigned_to'] ?: 'Unassigned'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="vol_admin_edit_task.php" style="display:inline;">
                                        <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
                                        <button class="edit-btn" type="submit">Edit</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
                                        <button class="delete-btn" type="submit" name="delete_task" onclick="return confirm('Are you sure you want to delete this task?');">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No tasks available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>