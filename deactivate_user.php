<?php
include 'connection.php'; // Include your database connection file

function deactivateUser($userId, $userType) {
    global $conn;

    // Determine the table and ID field based on user type
    $table = '';
    $idField = '';
    switch ($userType) {
        case 'donor':
            $table = 'donors';
            $idField = 'donor_id';
            break;
        case 'volunteer':
            $table = 'volunteers';
            $idField = 'volunteer_id';
            break;
        case 'organization':
            $table = 'organizations';
            $idField = 'id';
            break;
        default:
            echo "Invalid user type.";
            return;
    }

    // Fetch the original password
    $query = "SELECT password FROM $table WHERE $idField = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "User not found.";
        return;
    }
    $row = $result->fetch_assoc();
    $originalPassword = $row['password'];

    // Backup the original password
    $query = "INSERT INTO password_backup (user_id, user_type, original_password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $userId, $userType, $originalPassword);
    $stmt->execute();

    // Set a temporary password
    $temporaryPassword = md5(uniqid(rand(), true));
    $query = "UPDATE $table SET password = ? WHERE $idField = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $temporaryPassword, $userId);
    $stmt->execute();

    echo "User deactivated successfully.";
}