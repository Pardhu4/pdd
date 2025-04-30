<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "surplus_to_serve";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input
    $email = $conn->real_escape_string(trim($_POST['email']));
    $volunteerId = $conn->real_escape_string(trim($_POST['volunteer_id']));
    $password = trim($_POST['password']);

    // Query to verify the volunteer
    $query = "SELECT * FROM volunteers WHERE email = '$email' AND volunteer_id = '$volunteerId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $volunteer = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $volunteer['password'])) {
            echo "<script>
                    alert('Login successful! Welcome back, Volunteer ID: $volunteerId');
                    window.location.href = 'volunteer_dashboard.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = 'volunteer_login.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid email or Volunteer ID. Please try again.');
                window.location.href = 'volunteer_login.html';
              </script>";
    }

    // Close the connection
    $conn->close();
}
?>