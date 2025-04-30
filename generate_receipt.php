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

// Get POST data
$reference_id = isset($_POST['reference_id']) ? $_POST['reference_id'] : '';
$donor_id = isset($_POST['donor_id']) ? intval($_POST['donor_id']) : 0;

// Validate inputs
if (empty($reference_id) || empty($donor_id)) {
    die("<p class='error'>Both Reference ID and Donor ID are required.</p>");
}

// Fetch payment and donor details
$sql = "
    SELECT p.reference_id, p.payment_method, p.payment_status, p.amount, p.created_at, d.donor_name 
    FROM payments p 
    JOIN donors d ON p.donor_id = d.donor_id 
    WHERE p.reference_id = ? AND p.donor_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $reference_id, $donor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Receipt</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                padding: 20px;
                background-color: #f4f4f9;
                color: #333;
            }
            .receipt-container {
                max-width: 600px;
                margin: 0 auto;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            h1, h2 {
                color: #007bff;
                text-align: center;
            }
            p {
                margin: 10px 0;
                font-size: 16px;
            }
            .print-button {
                margin-top: 20px;
                text-align: center;
            }
            button {
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="receipt-container">
            <h1>Payment Receipt</h1>
            <h2>Thank you, <?php echo htmlspecialchars($data['donor_name']); ?>!</h2>
            <p><strong>Reference ID:</strong> <?php echo htmlspecialchars($data['reference_id']); ?></p>
            <p><strong>Donor ID:</strong> <?php echo htmlspecialchars($donor_id); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($data['payment_method']); ?></p>
            <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($data['payment_status']); ?></p>
            <p><strong>Amount Paid:</strong> $<?php echo htmlspecialchars($data['amount']); ?></p>
            <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($data['created_at']); ?></p>
            <div class="print-button">
                <button onclick="window.print()">Print Receipt</button>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "<p class='error'>No payment found with the provided Reference ID and Donor ID.</p>";
}

$stmt->close();
$conn->close();
?>