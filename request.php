<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
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

    // Sanitize form input
    $org_name = $conn->real_escape_string($_POST['org_name']);
    $reg_number = $conn->real_escape_string($_POST['reg_number']);
    $contact_person = $conn->real_escape_string($_POST['contact_person']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $request_type = $conn->real_escape_string($_POST['request_type']);
    $additional_details = $conn->real_escape_string($_POST['additional_details']);
    $request_date = date("Y-m-d H:i:s");

    // Generate unique reference number
    $reference_number = strtoupper(uniqid("REQ-"));

    // Insert the data into the database
    $sql = "INSERT INTO organization_requests (reference_number, org_name, reg_number, contact_person, email, address, request_type, additional_details, request_date, status)
            VALUES ('$reference_number', '$org_name', '$reg_number', '$contact_person', '$email', '$address', '$request_type', '$additional_details', '$request_date', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        // Return the success message with the reference number
        echo json_encode(['success' => true, 'reference_number' => $reference_number]);
    } else {
        // Return error message if query fails
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }

    $conn->close();
}
?>