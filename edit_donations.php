<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$dbname = "surplus_to_serve";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM donations WHERE id = $delete_id";
    if ($conn->query($delete_query) === TRUE) {
        echo "<script>alert('Record deleted successfully!'); window.location.href='edit_donations.php';</script>";
    } else {
        echo "<script>alert('Error deleting record!');</script>";
    }
}

// Handle edit request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $donation_type = $_POST['donation_type'];
    $food_type = $_POST['food_type'];
    $clothes_type = $_POST['clothes_type'];
    $clothes_quantity = $_POST['clothes_quantity'];
    $details = $_POST['details'];
    $pickup_location = $_POST['pickup_location'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $update_query = "UPDATE donations SET 
                        donation_type='$donation_type', 
                        food_type='$food_type', 
                        clothes_type='$clothes_type', 
                        clothes_quantity='$clothes_quantity', 
                        details='$details', 
                        pickup_location='$pickup_location', 
                        name='$name', 
                        phone='$phone'
                    WHERE id = $update_id";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Record updated successfully!'); window.location.href='edit_donations.php';</script>";
    } else {
        echo "<script>alert('Error updating record!');</script>";
    }
}

// Fetch all donation records
$donations_result = $conn->query("SELECT * FROM donations");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #ff4d6d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .form-container {
            display: none;
            margin-bottom: 20px;
        }

        .form-container.active {
            display: block;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Donations</h1>
        <!-- Display Donations -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Donation Type</th>
                    <th>Food Type</th>
                    <th>Clothes Type</th>
                    <th>Clothes Quantity</th>
                    <th>Details</th>
                    <th>Pickup Location</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $donations_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['donation_type']; ?></td>
                        <td><?php echo $row['food_type']; ?></td>
                        <td><?php echo $row['clothes_type']; ?></td>
                        <td><?php echo $row['clothes_quantity']; ?></td>
                        <td><?php echo $row['details']; ?></td>
                        <td><?php echo $row['pickup_location']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td>
                            <button class="btn btn-edit" onclick="editRecord(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="edit_donations.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Edit Form -->
        <div class="form-container" id="editForm">
            <form method="POST" action="">
                <input type="hidden" id="update_id" name="update_id">
                <label for="donation_type">Donation Type</label>
                <input type="text" id="donation_type" name="donation_type" required>

                <label for="food_type">Food Type</label>
                <input type="text" id="food_type" name="food_type">

                <label for="clothes_type">Clothes Type</label>
                <input type="text" id="clothes_type" name="clothes_type">

                <label for="clothes_quantity">Clothes Quantity</label>
                <input type="number" id="clothes_quantity" name="clothes_quantity">

                <label for="details">Details</label>
                <textarea id="details" name="details"></textarea>

                <label for="pickup_location">Pickup Location</label>
                <input type="text" id="pickup_location" name="pickup_location" required>

                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" required>

                <button type="submit">Update Record</button>
            </form>
        </div>
    </div>

    <script>
        function editRecord(record) {
            document.getElementById('editForm').classList.add('active');
            document.getElementById('update_id').value = record.id;
            document.getElementById('donation_type').value = record.donation_type;
            document.getElementById('food_type').value = record.food_type;
            document.getElementById('clothes_type').value = record.clothes_type;
            document.getElementById('clothes_quantity').value = record.clothes_quantity;
            document.getElementById('details').value = record.details;
            document.getElementById('pickup_location').value = record.pickup_location;
            document.getElementById('name').value = record.name;
            document.getElementById('phone').value = record.phone;
        }
    </script>
</body>
</html>
