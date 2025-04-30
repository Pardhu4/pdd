<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    // Redirect to login page if not logged in
    header("Location: admin_login.html");
    exit;
}

// Check if an ID is passed in the URL
if (!isset($_GET['id'])) {
    echo "No donation ID specified.";
    exit;
}

// Get the donation ID from the URL and sanitize it
$donation_id = (int)$_GET['id']; // Casting to int to ensure it's an integer

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'surplus_to_serve');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch donation information based on the ID using prepared statement
$sql = "SELECT id, donation_type, name, contact_number, pickup_location FROM donations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $donation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $donation = $result->fetch_assoc();
} else {
    echo "No donation found with that ID.";
    exit;
}

// Success message initialization
$success_message = '';

// If the form is submitted, update the donation data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    // Sanitize and validate inputs
    $donation_type = htmlspecialchars($_POST['donation_type']);
    $name = htmlspecialchars($_POST['name']);
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $pickup_location = htmlspecialchars($_POST['pickup_location']);

    // Prepared statement for updating the donation record
    $update_sql = "UPDATE donations 
                   SET donation_type = ?, name = ?, contact_number = ?, pickup_location = ? 
                   WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $donation_type, $name, $contact_number, $pickup_location, $donation_id);

    if ($update_stmt->execute()) {
        // Set success message
        $success_message = "Donation updated successfully!";
    } else {
        echo "Error updating donation: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Donation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"], .btn {
            padding: 10px 15px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            text-decoration: none;
        }
        input[type="submit"]:hover, .btn:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h2>Edit Donation</h2>

<!-- Display Success Message if available -->
<?php if ($success_message): ?>
    <div class="success-message">
        <?= $success_message ?>
    </div>
<?php endif; ?>

<!-- Edit Donation Form -->
<form method="POST">
    <label for="donation_type">Donation Type:</label>
    <input type="text" name="donation_type" value="<?= htmlspecialchars($donation['donation_type']) ?>" required>

    <label for="name">Donor Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($donation['name']) ?>" required>

    <label for="contact_number">Contact Number:</label>
    <input type="text" name="contact_number" value="<?= htmlspecialchars($donation['contact_number']) ?>" required>

    <label for="pickup_location">Pickup Location:</label>
    <input type="text" name="pickup_location" value="<?= htmlspecialchars($donation['pickup_location']) ?>" required>

    <input type="submit" name="update" value="Update Donation">
</form>

<!-- Back Button -->
<a href="admin_dashboard.php" class="btn">Back to Donations</a>

</body>
</html>