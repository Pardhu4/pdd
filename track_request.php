<?php
header("Content-Type: application/json");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "surplus_to_serve";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

// Retrieve form data
$reference_number = $_POST['reference_number'];
$email = $_POST['email'];

// Check for matching request
$sql = "SELECT status FROM organization_requests WHERE reference_number = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $reference_number, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["success" => true, "status" => $row['status']]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid reference number or email."]);
}

$stmt->close();
$conn->close();
?>