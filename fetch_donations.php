<?php
// Database connection
$servername = "localhost";
$username = "root";  // Change this to your database username
$password = "";      // Change this to your database password
$dbname = "surplus_to_serve";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to fetch payment data
$sql = "SELECT p.id, p.created_at, d.donor_name, p.amount
        FROM payments p
        JOIN donors d ON p.donor_id = d.donor_id
        WHERE p.payment_status = 'completed'";  // Assuming we want only completed payments

$result = $conn->query($sql);

$donations = [];

if ($result->num_rows > 0) {
    // Fetch all rows as associative arrays
    while($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
}

// Return data as JSON
echo json_encode($donations);

// Close connection
$conn->close();
?>