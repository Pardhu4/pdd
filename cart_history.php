<?php
// Database configuration
$host = "localhost";
$dbname = "surplus_to_serve";
$username = "root";
$password = "";

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize variables
    $orderHistory = [];
    $error_message = "";

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reg_number = $_POST['reg_number'];
        $email = $_POST['email'];

        // Validate organization credentials
        $orgQuery = "
            SELECT reg_number 
            FROM organizations 
            WHERE reg_number = :reg_number AND email = :email
        ";
        $stmt = $conn->prepare($orgQuery);
        $stmt->execute([':reg_number' => $reg_number, ':email' => $email]);
        $organization = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($organization) {
            // Fetch order history from cart
            $cartQuery = "
                SELECT c.item_id, c.item_name, c.quantity, c.created_at 
                FROM cart c 
                WHERE c.reg_number = :reg_number
                ORDER BY c.created_at DESC
            ";
            $cartStmt = $conn->prepare($cartQuery);
            $cartStmt->execute([':reg_number' => $reg_number]);
            $orderHistory = $cartStmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error_message = "Invalid Registration Number or Email.";
        }
    }
} catch (PDOException $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        /* Styling */
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 40px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #2a2a2a; }
        form { display: flex; flex-direction: column; gap: 15px; margin-bottom: 20px; }
        input[type="text"], input[type="email"] { padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 15px; background-color: #ff4d6d; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #2a2a2a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007BFF; color: #fff; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .error { color: red; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
<button onclick="goBack()" style="background-color: #ff4d6d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Go Back</button>

<div class="container">
    <h1>View Order History</h1>

    <!-- Form to Input reg_number and email -->
    <form method="POST">
        <input type="text" name="reg_number" placeholder="Enter Registration Number" required>
        <input type="email" name="email" placeholder="Enter Email Address" required>
        <button type="submit">View History</button>
    </form>

    <!-- Error Message -->
    <?php if (!empty($error_message)): ?>
        <div class="error"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <!-- Order History Table -->
    <?php if (!empty($orderHistory)): ?>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderHistory as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['item_id']); ?></td>
                        <td><?= htmlspecialchars($order['item_name']); ?></td>
                        <td><?= htmlspecialchars($order['quantity']); ?></td>
                        <td><?= htmlspecialchars($order['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($orderHistory)): ?>
        <p>No order history found for the given Registration Number and Email.</p>
    <?php endif; ?>
</div>
</body>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</html>
