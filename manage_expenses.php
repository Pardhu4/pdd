
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

// Handle form submission to add an expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_expense"])) {
    $expense_name = $_POST["expense_name"];
    $amount = $_POST["amount"];
    $category = $_POST["category"];
    $expense_date = $_POST["expense_date"];

    $sql = "INSERT INTO expenses (expense_name, amount, category, expense_date) VALUES ('$expense_name', $amount, '$category', '$expense_date')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense added successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle deletion
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM expenses WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense deleted successfully!');</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_expense"])) {
    $id = $_POST["id"];
    $expense_name = $_POST["expense_name"];
    $amount = $_POST["amount"];
    $category = $_POST["category"];
    $expense_date = $_POST["expense_date"];

    $sql = "UPDATE expenses SET expense_name='$expense_name', amount=$amount, category='$category', expense_date='$expense_date' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense updated successfully!');</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch all expenses
$expenses = [];
$sql = "SELECT * FROM expenses ORDER BY expense_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Expenses</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 50px;
    }

    .title {
      text-align: center;
      color: #2a2a2a;
    }

    form {
      margin-bottom: 20px;
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
      background: #ff4d6d;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    form button:hover {
      background: #2a2a2a;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      padding-left: 10px;
    }

    table th,
    table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    table th {
      background: #ff4d6d;
      color: white;
      padding: 50px;
    }

    .action-btn {
      background: #007bff;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      text-decoration: none;
    }

    .action-btn.edit {
      background: #28a745;
      margin-right: 20px;
    }

    .action-btn.delete {
      background: #dc3545;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="title">Manage Expenses</h1>

    <!-- Add Expense Form -->
    <form method="POST" action="">
      <input type="text" name="expense_name" placeholder="Expense Name" required>
      <input type="number" step="0.01" name="amount" placeholder="Amount" required>
      <select name="category" required>
        <option value="">Select Category</option>
        <option value="Food">Food</option>
        <option value="Transport">Transport</option>
        <option value="Utilities">Utilities</option>
        <option value="Miscellaneous">Miscellaneous</option>
      </select>
      <input type="date" name="expense_date" required>
      <button type="submit" name="add_expense">Add Expense</button>
    </form>

    <!-- Expenses Table -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Expense Name</th>
          <th>Amount ($)</th>
          <th>Category</th>
          <th>Expense Date</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($expenses) > 0): ?>
          <?php foreach ($expenses as $expense): ?>
            <tr>
              <td><?php echo $expense['id']; ?></td>
              <td><?php echo $expense['expense_name']; ?></td>
              <td><?php echo number_format($expense['amount'], 2); ?></td>
              <td><?php echo $expense['category']; ?></td>
              <td><?php echo $expense['expense_date']; ?></td>
              <td><?php echo $expense['created_at']; ?></td>
              <td>
                <a href="edit_expense.php?id=<?php echo $expense['id']; ?>" class="action-btn edit">Edit</a>
                <a href="?delete=<?php echo $expense['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">No expenses recorded</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>