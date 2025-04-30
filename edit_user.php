<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.html");
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'surplus_to_serve');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$type || !$id) {
    echo "Invalid request.";
    exit;
}

// Define allowed types for security
$allowed_types = ['donor', 'organization', 'volunteer'];
if (!in_array($type, $allowed_types)) {
    echo "Invalid user type.";
    exit;
}

// Prepare SQL statement
$stmt = null;
switch ($type) {
    case 'donor':
        $stmt = $conn->prepare("SELECT donor_name AS name, email, contact_number, address FROM donors WHERE donor_id = ?");
        break;
    case 'organization':
        $stmt = $conn->prepare("SELECT org_name AS name, email, contact_person AS contact_number, address FROM organizations WHERE id = ?");
        break;
    case 'volunteer':
        $stmt = $conn->prepare("SELECT name, email, mobile AS contact_number FROM volunteers WHERE volunteer_id = ?");
        break;
}

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "No user found with the provided ID.";
    exit;
}

$user = $result->fetch_assoc();

// Handle form submission for updating user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_name = htmlspecialchars(trim($_POST['name']));
    $updated_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $updated_contact_number = htmlspecialchars(trim($_POST['contact_number']));
    $updated_address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : null;

    if (empty($updated_name) || empty($updated_email) || empty($updated_contact_number)) {
        echo "<p style='color: red;'>Please fill out all required fields.</p>";
    } else {
        // Prepare update statements
        switch ($type) {
            case 'donor':
                $stmt = $conn->prepare("UPDATE donors SET donor_name = ?, email = ?, contact_number = ?, address = ? WHERE donor_id = ?");
                $stmt->bind_param('ssssi', $updated_name, $updated_email, $updated_contact_number, $updated_address, $id);
                break;
            case 'organization':
                $stmt = $conn->prepare("UPDATE organizations SET org_name = ?, email = ?, contact_person = ?, address = ? WHERE id = ?");
                $stmt->bind_param('ssssi', $updated_name, $updated_email, $updated_contact_number, $updated_address, $id);
                break;
            case 'volunteer':
                $stmt = $conn->prepare("UPDATE volunteers SET name = ?, email = ?, mobile = ? WHERE volunteer_id = ?");
                $stmt->bind_param('sssi', $updated_name, $updated_email, $updated_contact_number, $id);
                break;
        }

        if ($stmt->execute()) {
            echo "<p class='success-msg'>User details updated successfully.</p>";
        } else {
            echo "<p class='error-msg'>Error updating user: " . $conn->error . "</p>";
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        h2 {
            color: #333;
            text-align: center;
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            animation: popIn 0.5s ease-in-out;
        }

        @keyframes popIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-msg, .error-msg {
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: box-shadow 0.3s ease;
        }

        input:focus, textarea:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        .btn {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: block;
            margin: 10px auto;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 15px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: block;
            width: max-content;
            margin-left: auto;
            margin-right: auto;
        }

        .back-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<h2>Edit <?php echo ucfirst($type); ?> Details</h2>

<div class="form-container">
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" required>

        <?php if ($type !== 'volunteer'): ?>
        <label for="address">Address:</label>
        <textarea id="address" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
        <?php endif; ?>

        <input type="submit" value="Update User" class="btn">
    </form>

    <a href="manage_users.php" class="back-btn">Back to User Management</a>
</div>

</body>
</html>