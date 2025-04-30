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

    // Fetch all Hotels
    $hotelQuery = $conn->prepare("SELECT partner_id, name FROM partners WHERE category = 'Hotel'");
    $hotelQuery->execute();
    $hotels = $hotelQuery->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all Grocery Stores
    $groceryQuery = $conn->prepare("SELECT partner_id, name FROM partners WHERE category = 'Grocery'");
    $groceryQuery->execute();
    $groceries = $groceryQuery->fetchAll(PDO::FETCH_ASSOC);

    // Fetch items for a specific partner
    $items = [];
    $partnerName = "";
    if (isset($_GET['partner_id'])) {
        $partnerId = $_GET['partner_id'];

        // Fetch partner name
        $partnerNameQuery = $conn->prepare("SELECT name FROM partners WHERE partner_id = :partner_id");
        $partnerNameQuery->bindParam(':partner_id', $partnerId, PDO::PARAM_STR);
        $partnerNameQuery->execute();
        $partnerName = $partnerNameQuery->fetchColumn();

        // Fetch items using the provided partner_id
        $itemsQuery = $conn->prepare("SELECT item_id, item_name, quantity FROM items WHERE partner_id = :partner_id");
        $itemsQuery->bindParam(':partner_id', $partnerId, PDO::PARAM_STR);
        $itemsQuery->execute();
        $items = $itemsQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    // Handle adding items to cart
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $itemId = $_POST['item_id'];
        $itemName = $_POST['item_name'];
        $quantityToCart = intval($_POST['quantity']);
        $regNumber = $_POST['reg_number'];

        // Start a transaction
        $conn->beginTransaction();

        try {
            // Fetch current quantity of the item
            $quantityQuery = $conn->prepare("SELECT quantity FROM items WHERE item_id = :item_id");
            $quantityQuery->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $quantityQuery->execute();
            $currentQuantity = intval($quantityQuery->fetchColumn());

            if ($currentQuantity >= $quantityToCart) {
                // Reduce the quantity in the items table
                $updateQuery = $conn->prepare("UPDATE items SET quantity = quantity - :quantity WHERE item_id = :item_id");
                $updateQuery->bindParam(':quantity', $quantityToCart, PDO::PARAM_INT);
                $updateQuery->bindParam(':item_id', $itemId, PDO::PARAM_INT);
                $updateQuery->execute();

                // Insert into cart table
                $cartQuery = $conn->prepare("INSERT INTO cart (item_id, item_name, quantity, reg_number) VALUES (:item_id, :item_name, :quantity, :reg_number)");
                $cartQuery->bindParam(':item_id', $itemId, PDO::PARAM_INT);
                $cartQuery->bindParam(':item_name', $itemName, PDO::PARAM_STR);
                $cartQuery->bindParam(':quantity', $quantityToCart, PDO::PARAM_INT);
                $cartQuery->bindParam(':reg_number', $regNumber, PDO::PARAM_STR);
                $cartQuery->execute();

                // Commit the transaction
                $conn->commit();
                $successMessage = "Item added to cart successfully!";
            } else {
                throw new Exception("Not enough stock available.");
            }
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollBack();
            $errorMessage = $e->getMessage();
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
    <title>Partners and Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #ff4d6d;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        ul li form {
            display: flex;
            align-items: center;
        }

        ul li input[type="number"] {
            width: 50px;
            margin-right: 10px;
        }

        button {
            padding: 8px 15px;
            background-color: #ff4d6d;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2a2a2a;
        }

        .cart-history {
            text-align: center;
            margin-top: 20px;
        }

        .cart-history a {
            color: #ff4d6d;
            text-decoration: none;
            font-size: 16px;
        }

        .cart-history a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Partners and Items</h1>
        
        <div class="hotels-section">
            <h2>Hotels</h2>
            <ul>
                <?php foreach ($hotels as $hotel): ?>
                    <li><a href="?partner_id=<?= htmlspecialchars($hotel['partner_id']); ?>"><?= htmlspecialchars($hotel['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="groceries-section">
            <h2>Grocery Stores</h2>
            <ul>
                <?php foreach ($groceries as $grocery): ?>
                    <li><a href="?partner_id=<?= htmlspecialchars($grocery['partner_id']); ?>"><?= htmlspecialchars($grocery['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (isset($_GET['partner_id'])): ?>
            <div class="items-section">
                <h2>Available Items from <?= htmlspecialchars($partnerName); ?></h2>
                <ul>
                    <?php foreach ($items as $item): ?>
                        <li>
                            <?= htmlspecialchars($item['item_name']); ?> (Quantity: <?= htmlspecialchars($item['quantity']); ?>)
                            <form method="POST">
                                <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['item_id']); ?>">
                                <input type="hidden" name="item_name" value="<?= htmlspecialchars($item['item_name']); ?>">
                                <input type="number" name="quantity" min="1" max="<?= htmlspecialchars($item['quantity']); ?>" required>
                                <input type="hidden" name="reg_number" value="12345">
                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <p style="color: green; text-align: center;"><?= htmlspecialchars($successMessage); ?></p>
        <?php elseif (isset($errorMessage)): ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <div class="cart-history">
            <a href="cart_history.php">View Cart History</a>
        </div>
    </div>
</body>
</html>
