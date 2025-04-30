<?php
// Database configuration
$host = 'localhost';
$dbname = 'surplus_to_serve';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start the session
    session_start();

    // Check if the partner is logged in
    if (!isset($_SESSION['partner_id'])) {
        die("Unauthorized access. Please log in.");
    }

    // Get the logged-in partner's ID
    $partner_id = $_SESSION['partner_id'];

    // Initialize variables
    $items = [];
    $successMessage = '';

    // Handle form actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            // Add new item
            $item_name = $_POST['item_name'];
            $quantity = $_POST['quantity'];

            $query = "INSERT INTO items (item_name, quantity, partner_id) VALUES (:item_name, :quantity, :partner_id)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':item_name' => $item_name,
                ':quantity' => $quantity,
                ':partner_id' => $partner_id
            ]);

            $_SESSION['success_message'] = "Item added successfully!";
        } elseif ($action === 'edit') {
            // Edit an existing item
            $item_id = $_POST['item_id'];
            $item_name = $_POST['item_name'];
            $quantity = $_POST['quantity'];

            $query = "UPDATE items SET item_name = :item_name, quantity = :quantity WHERE item_id = :item_id AND partner_id = :partner_id";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':item_name' => $item_name,
                ':quantity' => $quantity,
                ':item_id' => $item_id,
                ':partner_id' => $partner_id
            ]);

            $_SESSION['success_message'] = "Item updated successfully!";
        } elseif ($action === 'delete') {
            // Delete an item
            $item_id = $_POST['item_id'];

            $query = "DELETE FROM items WHERE item_id = :item_id AND partner_id = :partner_id";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':item_id' => $item_id,
                ':partner_id' => $partner_id
            ]);

            $_SESSION['success_message'] = "Item deleted successfully!";
        }

        // Redirect to prevent form resubmission and display success message
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Fetch items for the logged-in partner
    $query = "SELECT * FROM items WHERE partner_id = :partner_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':partner_id' => $partner_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check for a success message in the session
    if (isset($_SESSION['success_message'])) {
        $successMessage = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #ff4d6d;
            color: white;
        }

        button {
            background: #ff4d6d;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px 0;
        }

        button:hover {
            background: #2a2a2a;
        }

        .form-container {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome to Your Dashboard</h1>

    <?php if ($successMessage): ?>
        <div class="success-message">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <h2>Add New Item</h2>
        <form method="POST">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" id="item_name" required>

            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" required>

            <button type="submit" name="action" value="add">Add Item</button>
        </form>
    </div>

    <h2>Your Surplus Items</h2>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td class="actions">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                <input type="text" name="item_name" placeholder="Edit name" required value="<?= $item['item_name'] ?>">
                                <input type="number" name="quantity" placeholder="Edit quantity" required value="<?= $item['quantity'] ?>">
                                <button type="submit" name="action" value="edit">Save</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                <button type="submit" name="action" value="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No items found. Add some surplus items.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
