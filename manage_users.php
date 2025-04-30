<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.html");
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'surplus_to_serve');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If a delete request is received
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_type = $_POST['delete_type'];

    switch ($delete_type) {
        case 'donor':
            $delete_sql = "DELETE FROM donors WHERE donor_id = $delete_id";
            break;
        case 'organization':
            $delete_sql = "DELETE FROM organizations WHERE id = $delete_id";
            break;
        case 'volunteer':
            $delete_sql = "DELETE FROM volunteers WHERE volunteer_id = $delete_id";
            break;
        default:
            echo "Invalid user type.";
            exit;
    }

    if ($conn->query($delete_sql) === TRUE) {
        echo "<p class='success-msg'>User with ID $delete_id deleted successfully.</p>";
    } else {
        echo "<p class='error-msg'>Error deleting record: " . $conn->error . "</p>";
    }
}

// Fetch data from all user tables
$donor_sql = "SELECT donor_id, donor_name, email, contact_number, address, registration_date FROM donors";
$donor_result = $conn->query($donor_sql);

$organization_sql = "SELECT id, org_name, reg_number, contact_person, email, phone_number, address FROM organizations";
$organization_result = $conn->query($organization_sql);

$volunteer_sql = "SELECT volunteer_id, name, email, mobile FROM volunteers";
$volunteer_result = $conn->query($volunteer_sql);

echo "
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background: #f4f6f9;
        color: #333;
    }
    h2, h3 {
        color: #ff4d6d;
        text-align: center;
        animation: fadeIn 1s;
    }
    h3 {
        margin-top: 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #007bff;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
        transition: background-color 0.3s;
    }
    .btn-danger, .btn-edit {
        display: inline-block;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        color: white;
    }
    .btn-danger {
        background-color: #dc3545;
        transition: background-color 0.3s ease;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .btn-edit {
        background-color: #007bff;
        transition: background-color 0.3s ease;
    }
    .btn-edit:hover {
        background-color: #0056b3;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #ff4d6d;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
    }
    .btn:hover {
        background-color: #2a2a2a;
    }
    .success-msg, .error-msg {
        text-align: center;
        padding: 10px;
        margin: 15px 0;
        border-radius: 4px;
        animation: fadeIn 1s;
    }
    .success-msg {
        background-color: #d4edda;
        color: #155724;
    }
    .error-msg {
        background-color: #f8d7da;
        color: #721c24;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
";

echo "<h2>Manage Users</h2>";
echo "<a href='admin.php' class='btn'>Back to Dashboard</a>";

echo "<h3>Donors</h3>";
if ($donor_result->num_rows > 0) {
    echo "<table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Address</th>
            <th>Registration Date</th>
            <th>Actions</th>
        </tr>";
    while ($row = $donor_result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row['donor_id'] . "</td>
            <td>" . $row['donor_name'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['contact_number'] . "</td>
            <td>" . $row['address'] . "</td>
            <td>" . $row['registration_date'] . "</td>
            <td>
                <form method='POST' style='display: inline;' onsubmit='return confirm(\"Are you sure you want to delete this donor?\");'>
                    <input type='hidden' name='delete_id' value='" . $row['donor_id'] . "'>
                    <input type='hidden' name='delete_type' value='donor'>
                    <input type='submit' value='Delete' class='btn-danger'>
                </form>
                <a href='edit_user.php?type=donor&id=" . $row['donor_id'] . "' class='btn-edit'>Edit</a>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No donors found.</p>";
}

// Similar table outputs for organizations and volunteers...

// Organizations Table
echo "<h3>Organizations</h3>";
if ($organization_result->num_rows > 0) {
    echo "<table>
        <tr>
            <th>ID</th>
            <th>Organization Name</th>
            <th>Registration Number</th>
            <th>Contact Person</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>";
    while ($row = $organization_result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row['id'] . "</td>
            <td>" . $row['org_name'] . "</td>
            <td>" . $row['reg_number'] . "</td>
            <td>" . $row['contact_person'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['phone_number'] . "</td>
            <td>" . $row['address'] . "</td>
            <td>
                <form method='POST' style='display: inline;' onsubmit='return confirm(\"Are you sure you want to delete this organization?\");'>
                    <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                    <input type='hidden' name='delete_type' value='organization'>
                    <input type='submit' value='Delete' class='btn-danger'>
                </form>
                <a href='edit_user.php?type=organization&id=" . $row['id'] . "' class='btn-edit'>Edit</a>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No organizations found.";
}

// Volunteers Table
echo "<h3>Volunteers</h3>";
if ($volunteer_result->num_rows > 0) {
    echo "<table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Actions</th>
        </tr>";
    while ($row = $volunteer_result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row['volunteer_id'] . "</td>
            <td>" . $row['name'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['mobile'] . "</td>
            <td>
                <form method='POST' style='display: inline;' onsubmit='return confirm(\"Are you sure you want to delete this volunteer?\");'>
                    <input type='hidden' name='delete_id' value='" . $row['volunteer_id'] . "'>
                    <input type='hidden' name='delete_type' value='volunteer'>
                    <input type='submit' value='Delete' class='btn-danger'>
                </form>
                <a href='edit_user.php?type=volunteer&id=" . $row['volunteer_id'] . "' class='btn-edit'>Edit</a>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No volunteers found.";
}

$conn->close();
?>