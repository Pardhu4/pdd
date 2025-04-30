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

// Check if the form was submitted with the task ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_id'], $_POST['task_name'], $_POST['task_description'])) {
    $task_id = intval($_POST['task_id']);
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];

    // Prepare the SQL query to update the task
    $sql = "UPDATE tasks SET task_name = ?, task_description = ? WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
    
    // Execute the query and check for success
    if ($stmt->execute()) {
        // Success: Show popup and redirect to admin_tasks.php
        echo "<script>
                alert('Task updated successfully!');
                window.location.href = 'admin_tasks.php';
              </script>";
        exit();
    } else {
        echo "<p>Error updating task: " . $stmt->error . "</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}

$conn->close();
?>