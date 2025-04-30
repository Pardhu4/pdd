<?php
// Database connection
$servername = "localhost";
$username = "root";  // Change this to your database username
$password = "";      // Change this to your database password
$dbname = "surplus_to_serve";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the data from POST request
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'];
$userId = $data['userId'];
$userType = $data['userType'];

// Function to handle deactivating the user
function deactivateUser($conn, $userId, $userType) {
    $table = "";
    $idColumn = "";

    // Determine the table and user ID column based on user type
    switch ($userType) {
        case 'donor':
            $table = "donors";
            $idColumn = "donor_id";
            break;
        case 'volunteer':
            $table = "volunteers";
            $idColumn = "volunteer_id";
            break;
        case 'organization':
            $table = "organizations";
            $idColumn = "id";
            break;
        default:
            return ['success' => false, 'message' => 'Invalid user type'];
    }

    // Retrieve the current password of the user
    $sql = "SELECT password FROM $table WHERE $idColumn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the current password
        $row = $result->fetch_assoc();
        $currentPassword = $row['password'];

        // Store the current password in the backup table
        $backupSql = "INSERT INTO password_backup (user_id, user_type, original_password) VALUES (?, ?, ?)";
        $backupStmt = $conn->prepare($backupSql);
        $backupStmt->bind_param("iss", $userId, $userType, $currentPassword);
        $backupStmt->execute();

        // Set a temporary password (you can choose a random password or a fixed one)
        $tempPassword = md5("temporarypassword");  // You can generate a better password

        // Update the user's password to the temporary one
        $updateSql = "UPDATE $table SET password = ? WHERE $idColumn = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $tempPassword, $userId);
        $updateStmt->execute();

        return ['success' => true, 'message' => 'User deactivated successfully.'];
    } else {
        return ['success' => false, 'message' => 'User not found.'];
    }
}

// Function to handle activating the user
function activateUser($conn, $userId, $userType) {
    $table = "";
    $idColumn = "";

    // Determine the table and user ID column based on user type
    switch ($userType) {
        case 'donor':
            $table = "donors";
            $idColumn = "donor_id";
            break;
        case 'volunteer':
            $table = "volunteers";
            $idColumn = "volunteer_id";
            break;
        case 'organization':
            $table = "organizations";
            $idColumn = "id";
            break;
        default:
            return ['success' => false, 'message' => 'Invalid user type'];
    }

    // Retrieve the original password from the backup table
    $backupSql = "SELECT original_password FROM password_backup WHERE user_id = ? AND user_type = ? ORDER BY id DESC LIMIT 1";
    $backupStmt = $conn->prepare($backupSql);
    $backupStmt->bind_param("is", $userId, $userType);
    $backupStmt->execute();
    $result = $backupStmt->get_result();

    if ($result->num_rows > 0) {
        // Get the original password
        $row = $result->fetch_assoc();
        $originalPassword = $row['original_password'];

        // Restore the original password
        $updateSql = "UPDATE $table SET password = ? WHERE $idColumn = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $originalPassword, $userId);
        $updateStmt->execute();

        // Optionally, remove the backup record after restoring the password
        $deleteSql = "DELETE FROM password_backup WHERE user_id = ? AND user_type = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("is", $userId, $userType);
        $deleteStmt->execute();

        return ['success' => true, 'message' => 'User activated successfully.'];
    } else {
        return ['success' => false, 'message' => 'No backup password found.'];
    }
}

// Perform the action based on the request
if ($action === 'deactivate') {
    $response = deactivateUser($conn, $userId, $userType);
} elseif ($action === 'activate') {
    $response = activateUser($conn, $userId, $userType);
} else {
    $response = ['success' => false, 'message' => 'Invalid action.'];
}

// Send response as JSON
echo json_encode($response);

// Close the connection
$conn->close();
?>