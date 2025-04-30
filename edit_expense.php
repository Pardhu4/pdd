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

// Fetch the expense details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM expenses WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $expense = $result->fetch_assoc();
    } else {
        die("Expense not found");
    }
}

// Handle form submission to update the expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_expense"])) {
    $id = $_POST["id"];
    $expense_name = $_POST["expense_name"];
    $amount = $_POST["amount"];
    $category = $_POST["category"];
    $expense_date = $_POST["expense_date"];

    $sql = "UPDATE expenses 
            SET expense_name='$expense_name', 
                amount=$amount, 
                category='$category', 
                expense_date='$expense_date' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense updated successfully!'); window.location.href='manage_expenses.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .title {
            text-align: center;
            color: #333;
        }

        form input,
        form select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Edit Expense</h1>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
            <input type="text" name="expense_name" placeholder="Expense Name" value="<?php echo $expense['expense_name']; ?>" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" value="<?php echo $expense['amount']; ?>" required>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Food" <?php if ($expense['category'] == 'Food') echo 'selected'; ?>>Food</option>
                <option value="Transport" <?php if ($expense['category'] == 'Transport') echo 'selected'; ?>>Transport</option>
                <option value="Utilities" <?php if ($expense['category'] == 'Utilities') echo 'selected'; ?>>Utilities</option>
                <option value="Miscellaneous" <?php if ($expense['category'] == 'Miscellaneous') echo 'selected'; ?>>Miscellaneous</option>
            </select>
            <input type="date" name="expense_date" value="<?php echo $expense['expense_date']; ?>" required>
            <button type="submit" name="update_expense">Update Expense</button>
        </form>
    </div>
</body>
</html>