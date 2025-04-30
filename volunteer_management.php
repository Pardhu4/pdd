<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f7f7f7;
        }
        header {
            background: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        form input, form button {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background: #4CAF50;
            color: white;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
<header>
    <h1>Admin Dashboard - Manage Volunteers</h1>
</header>
<div class="container">
    <!-- Update Stats -->
    <h2>Update Volunteer Stats</h2>
    <form method="POST">
        <input type="number" name="volunteer_id" placeholder="Volunteer ID" required>
        <input type="number" name="hours" placeholder="Hours Volunteered" required>
        <input type="number" name="tasks" placeholder="Tasks Completed" required>
        <button type="submit" name="update_stats">Update Stats</button>
    </form>

    <!-- Add Badge -->
    <h2>Add Badge</h2>
    <form method="POST">
        <input type="text" name="badge_name" placeholder="Badge Name" required>
        <input type="number" name="volunteer_id" placeholder="Volunteer ID" required>
        <button type="submit" name="add_badge">Add Badge</button>
    </form>

    <!-- Display Data -->
    <h2>Current Tasks</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Task Name</th>
            <th>Date</th>
            <th>Duration</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $tasks->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['task_name'] ?></td>
            <td><?= $row['task_date'] ?></td>
            <td><?= $row['duration'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>