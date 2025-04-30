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

// Check if task_id is provided (either via GET or POST)
$task_id = null;
if (isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']);
} elseif (isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
}

if ($task_id) {
    // Fetch task details
    $sql = "SELECT task_name, task_description FROM tasks WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc(); // Fetch task data
    } else {
        echo "<p>Task not found.</p>";
        exit(); // Stop further execution
    }
} else {
    // Redirect to the task list page if no task ID is provided
    header("Location: admin_tasks.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f9f9f9, #d1e8e2);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, textarea, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Task</h2>
        <form method="POST" action="update_task.php">
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
            
            <label for="task_name">Task Name:</label>
            <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
            
            <label for="task_description">Task Description:</label>
            <textarea name="task_description" required><?php echo htmlspecialchars($task['task_description']); ?></textarea>
            
            <button type="submit">Update Task</button>
        </form>
    </div>
</body>
</html>