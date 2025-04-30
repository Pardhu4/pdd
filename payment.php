<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "surplus_to_serve";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and validate form data
$payment_method = $_POST['payment_method'];
$amount = $_POST['amount']; // Retrieve the payment amount
$payment_status = "Success"; // Simulated payment status
$reference_id = uniqid("PAY"); // Unique reference ID

// Retrieve donor_id (you should have the donor_id available from the session or logged-in user)
$donor_id = $_POST['donor_id']; // Replace this with the actual donor_id from the session or authentication

// Validate input
if (!is_numeric($amount) || $amount <= 0) {
    echo "<script>
        alert('Invalid payment amount.');
        window.location.href = 'payment.html';
    </script>";
    exit;
}

// Ensure donor_id is valid and exists in the donors table
if (empty($donor_id) || !is_numeric($donor_id)) {
    echo "<script>
        alert('Invalid donor ID.');
        window.location.href = 'payment.html';
    </script>";
    exit;
}

// Check if donor_id exists in the donors table
$checkDonorQuery = "SELECT donor_id FROM donors WHERE donor_id = ?";
$checkStmt = $conn->prepare($checkDonorQuery);
$checkStmt->bind_param("i", $donor_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows === 0) {
    // Donor ID does not exist
    echo "<script>
        alert('Donor ID does not exist. Please check and try again.');
        window.location.href = 'payment.html';
    </script>";
    $checkStmt->close();
    $conn->close();
    exit;
}

$checkStmt->close();

// Save payment details to the database
$sql = "INSERT INTO payments (payment_method, payment_status, reference_id, amount, donor_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdi", $payment_method, $payment_status, $reference_id, $amount, $donor_id);

if ($stmt->execute()) {
    // On successful payment
    echo "<script>
        alert('Payment Successful! Reference ID: $reference_id');
        window.location.href = 'donor.html';
    </script>";
} else {
    // On payment failure
    echo "<script>
        alert('Payment Failed. Please try again.');
        window.location.href = 'payment.html';
    </script>";
}

$stmt->close();
$conn->close();
?>