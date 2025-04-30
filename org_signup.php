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
    $org_name = $conn->real_escape_string(trim($_POST['org_name']));
    $reg_number = $conn->real_escape_string(trim($_POST['reg_number']));
    $contact_person = $conn->real_escape_string(trim($_POST['contact_person']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $conn->real_escape_string(trim($_POST['password']));
    $phone_number = $conn->real_escape_string(trim($_POST['phone_number']));
    $address = $conn->real_escape_string(trim($_POST['address']));

    // Check if email or registration number already exists
    $checkQuery = "SELECT * FROM organizations WHERE email = '$email' OR reg_number = '$reg_number'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Email or Registration Number already registered!');
                window.location.href = 'org_signup.html';
              </script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into database
        $insertQuery = "INSERT INTO organizations (org_name, reg_number, contact_person, email, password, phone_number, address) 
                        VALUES ('$org_name', '$reg_number', '$contact_person', '$email', '$hashedPassword', '$phone_number', '$address')";

        if ($conn->query($insertQuery) === TRUE) {
            $orgId = $conn->insert_id; // Get the unique id
            echo "<script>
                    alert('Signup successful! Your Organization ID is: $orgId');
                    window.location.href = 'org_dashboard.html';
                  </script>";
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}
?>