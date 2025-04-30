<?php
// Connect to the database (reuse connection logic)
$conn = new mysqli('localhost', 'root', '', 'surplus_to_serve');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestId = $_POST['request_id'];
    if (isset($_POST['approve'])) {
        $updateStatus = "UPDATE organization_requests SET status='Approved' WHERE id=$requestId";
    } elseif (isset($_POST['reject'])) {
        $updateStatus = "UPDATE organization_requests SET status='Rejected' WHERE id=$requestId";
    }

    if ($conn->query($updateStatus) === TRUE) {
        echo "<p style='color: green;'>Request updated successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error updating request: " . $conn->error . "</p>";
    }
}

// Fetch requests to display
$sql = "SELECT id, org_name, reference_number, contact_person, address, request_type, status FROM organization_requests";
$result = $conn->query($sql);

echo "<h2>Approve Requests</h2>";

if ($result->num_rows > 0) {
    echo "
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #ff4d6d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #ff4d6d;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2a2a2a;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #2a2a2a;
        }
        form {
            display: inline;
        }
    </style>
    ";

    echo "<table>
            <tr>
                <th>ID</th>
                <th>Organization Name</th>
                <th>Reference Number</th>
                <th>Contact Person</th>
                <th>Address</th>
                <th>Request Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";

    // Display each row of the requests
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['org_name']}</td>
                <td>{$row['reference_number']}</td>
                <td>{$row['contact_person']}</td>
                <td>{$row['address']}</td>
                <td>{$row['request_type']}</td>
                <td>{$row['status']}</td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='request_id' value='{$row['id']}'>
                        <input type='submit' name='approve' value='Approve' class='btn'>
                        <input type='submit' name='reject' value='Reject' class='btn btn-danger'>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No requests found.</p>";
}

$conn->close();
?>