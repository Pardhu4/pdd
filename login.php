<?php
include 'connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to retrieve user with the provided email
    $query = $mysqli->prepare("SELECT * FROM donors WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            echo "Login successful";
            // Optionally, start a session here
            // session_start();
            // $_SESSION['user_id'] = $user['id'];
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No account found with this email";
    }

    $query->close();
}
$mysqli->close();
?>