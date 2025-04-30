<?php
function activateUser($userId, $userType) {
    global $conn;

    // Fetch the original password from the backup table
    $query = "SELECT original_password FROM password_backup WHERE user_id = ? AND user_type = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $userType);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "No backup found for this user.";
        return;
    }
    $row = $result->fetch_assoc();
    $originalPassword = $row['original_password'];

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

    // Restore the original password
    $query = "UPDATE $table SET password = ? WHERE $idField = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $originalPassword, $userId);
    $stmt->execute();

    // Delete the backup entry
    $query = "DELETE FROM password_backup WHERE user_id = ? AND user_type = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $userType);
    $stmt->execute();

    echo "User activated successfully.";
}