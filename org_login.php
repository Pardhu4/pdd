<?php
session_start();
include 'connection.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to fetch the user with the provided email
    $stmt = $mysqli->prepare("SELECT * FROM organizations WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching record was found
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify the provided password with the hashed password stored in the database
        if (password_verify($password, $row['password'])) {
            // Login successful
            echo "<script>
                    alert('Login successful!');
                    window.location.href = 'request.html';
                  </script>";
        } else {
            // Invalid password
            echo "<script>
                    alert('Invalid email or password. Please try again.');
                    window.location.href = 'org_login.html';
                  </script>";
        }
    } else {
        // No matching user found
        echo "<script>
                alert('Invalid email or password. Please try again.');
                window.location.href = 'org_login.html';
              </script>";
    }

    $stmt->close();
    $mysqli->close();
}
?>