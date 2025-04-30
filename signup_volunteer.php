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

    // Sanitize and validate input
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $mobile = $conn->real_escape_string(trim($_POST['phone'])); // Optional
    $password = $conn->real_escape_string(trim($_POST['password']));
    $address = isset($_POST['address']) ? $conn->real_escape_string(trim($_POST['address'])) : null; // Optional

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM volunteers WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Email is already registered!');
                window.location.href = 'signup.html';
              </script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into database
        $insertQuery = "INSERT INTO volunteers (name, email, mobile, password) 
                        VALUES ('$name', '$email', '$mobile', '$hashedPassword')";

        if ($conn->query($insertQuery) === TRUE) {
            $userId = $conn->insert_id; // Get the unique volunteer_id
            echo "<script>
                    alert('Signup successful! Your Volunteer ID is: $userId');
                    window.location.href = 'volunteer_dashboard.php';
                  </script>";
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}
?>