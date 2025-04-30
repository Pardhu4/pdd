<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Use your MySQL password
$dbname = "surplus_to_serve"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch payments data
$sql = "SELECT * FROM payments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Payments</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 900px;
      margin: 20px auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .title {
      text-align: center;
      color: #2a2a2a;
    }

    .payments-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    .payments-table th,
    .payments-table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    .payments-table th {
      background: #ff4d6d;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="title">View Payments</h1>
    <table class="payments-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Payment Method</th>
          <th>Payment Status</th>
          <th>Reference ID</th>
          <th>Amount ($)</th>
          <th>Created At</th>
          <th>Donor ID</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Check if there are any results
        if ($result->num_rows > 0) {
            // Output each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["payment_method"] . "</td>";
                echo "<td>" . $row["payment_status"] . "</td>";
                echo "<td>" . $row["reference_id"] . "</td>";
                echo "<td>$" . number_format($row["amount"], 2) . "</td>";
                echo "<td>" . $row["created_at"] . "</td>";
                echo "<td>" . $row["donor_id"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No payments found</td></tr>";
        }

        // Close the database connection
        $conn->close();
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>