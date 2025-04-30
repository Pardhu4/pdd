<?php
// Database configuration
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $donationType = !empty($_POST['donationType']) ? $conn->real_escape_string($_POST['donationType']) : null;
    $details = !empty($_POST['details']) ? $conn->real_escape_string($_POST['details']) : null;
    $pickupLocation = !empty($_POST['pickupLocation']) ? $conn->real_escape_string($_POST['pickupLocation']) : null;
    $name = !empty($_POST['name']) ? $conn->real_escape_string($_POST['name']) : null;
    $phone = !empty($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : null;
    $donorId = !empty($_POST['donorId']) ? intval($_POST['donorId']) : null;
    $foodType = isset($_POST['foodType']) && $_POST['donationType'] === 'food' ? $conn->real_escape_string($_POST['foodType']) : null;
    $clothesType = isset($_POST['clothesType']) && $_POST['donationType'] === 'clothes' ? $conn->real_escape_string($_POST['clothesType']) : null;
    $clothesQuantity = isset($_POST['clothesQuantity']) && $_POST['donationType'] === 'clothes' ? intval($_POST['clothesQuantity']) : null;

    // Check mandatory fields
    if (!$donationType || !$pickupLocation || !$name || !$phone || !$donorId) {
        echo "Error: Please fill in all required fields.";
        exit;
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO donations (donation_type, food_type, clothes_type, clothes_quantity, details, pickup_location, name, phone, donor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $donationType, $foodType, $clothesType, $clothesQuantity, $details, $pickupLocation, $name, $phone, $donorId);

    // Execute and handle success/error
    if ($stmt->execute()) {
        echo "<script>alert('Donation recorded successfully!'); window.location.href='donor.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='donor.html';</script>";
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>