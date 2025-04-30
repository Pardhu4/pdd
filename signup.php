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
    $email = $conn->real_escape_string(trim($_POST['email']));
    $name = $conn->real_escape_string(trim($_POST['name']));
    $password = $conn->real_escape_string(trim($_POST['password']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $address = $conn->real_escape_string(trim($_POST['address']));

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM donors WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Email already registered!');
                window.location.href = 'signup.html';
              </script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into database
        $insertQuery = "INSERT INTO donors (donor_name, email, password, contact_number, address) 
                        VALUES ('$name', '$email', '$hashedPassword', '$phone', '$address')";

        if ($conn->query($insertQuery) === TRUE) {
            $userId = $conn->insert_id; // Get the unique donor_id
            echo "<script>
                    alert('Signup successful! Your Unique ID is: $userId');
                    window.location.href = 'donor.html';
                  </script>";
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}
?>