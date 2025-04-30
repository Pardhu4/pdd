<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "surplus_to_serve";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get donor_id from URL or form
$donor_id = isset($_GET['donor_id']) ? intval($_GET['donor_id']) : 0;

if ($donor_id > 0) {
    // Fetch Donor Details
    $donorQuery = "SELECT * FROM donors WHERE donor_id = $donor_id";
    $donorResult = $conn->query($donorQuery);

    // Fetch Donations
    $donationQuery = "SELECT * FROM donations WHERE donor_id = $donor_id";
    $donationResult = $conn->query($donationQuery);

    // Fetch Payments (Adjusted Query)
    $paymentQuery = "
        SELECT id, payment_method, payment_status, reference_id, amount, created_at 
        FROM payments 
        WHERE donor_id = $donor_id
    ";
    $paymentResult = $conn->query($paymentQuery);

    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Donor Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f9;
                color: #333;
                line-height: 1.6;
            }
            .container {
                max-width: 800px;
                margin: 20px auto;
                background: #fff;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            h1, h2 {
                color: #007bff;
                text-align: center;
            }
            table {
                width: 100%;
                margin-top: 20px;
                border-collapse: collapse;
            }
            table th, table td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
            }
            table th {
                background-color: #007bff;
                color: #fff;
            }
            table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            p {
                margin: 10px 0;
            }
            .no-data {
                color: #888;
                font-style: italic;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class='container'>";

    if ($donorResult->num_rows > 0) {
        // Display Donor Details
        $donor = $donorResult->fetch_assoc();
        echo "<h1>Donor Details</h1>";
        echo "<p><strong>Name:</strong> " . $donor['donor_name'] . "</p>";
        echo "<p><strong>Email:</strong> " . $donor['email'] . "</p>";
        echo "<p><strong>Contact Number:</strong> " . $donor['contact_number'] . "</p>";
        echo "<p><strong>Address:</strong> " . $donor['address'] . "</p>";
        echo "<p><strong>Registration Date:</strong> " . $donor['registration_date'] . "</p>";

        // Display Donations
        echo "<h2>Donations</h2>";
        if ($donationResult->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Donation ID</th><th>Donation Type</th><th>Food Type</th><th>Clothes Type</th><th>Quantity</th><th>Pickup Location</th><th>Details</th></tr>";
            while ($donation = $donationResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $donation['id'] . "</td>";
                echo "<td>" . $donation['donation_type'] . "</td>";
                echo "<td>" . $donation['food_type'] . "</td>";
                echo "<td>" . $donation['clothes_type'] . "</td>";
                echo "<td>" . $donation['clothes_quantity'] . "</td>";
                echo "<td>" . $donation['pickup_location'] . "</td>";
                echo "<td>" . $donation['details'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-data'>No donations found for this donor.</p>";
        }

        // Display Payments
        echo "<h2>Payments</h2>";
        if ($paymentResult->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Payment ID</th><th>Method</th><th>Status</th><th>Reference ID</th><th>Amount</th><th>Created At</th></tr>";
            while ($payment = $paymentResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $payment['id'] . "</td>";
                echo "<td>" . $payment['payment_method'] . "</td>";
                echo "<td>" . $payment['payment_status'] . "</td>";
                echo "<td>" . $payment['reference_id'] . "</td>";
                echo "<td>" . $payment['amount'] . "</td>";
                echo "<td>" . $payment['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-data'>No payments found for this donor.</p>";
        }
    } else {
        echo "<p class='no-data'>No donor found with ID $donor_id.</p>";
    }
    echo '<a href="receipt_form.html">Click here to print receipt</a>';
    echo "</div>
    </body>
    </html>";
} else {
    echo "<p>Invalid donor ID.</p>";
}

// Close connection
$conn->close();
?>