<?php
// Database connection credentials
$host = 'localhost';
$dbname = 'surplus_to_serve';
$username = 'root';
$password = '';

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all stories
    $stmt = $pdo->query("SELECT * FROM impact_us ORDER BY created_at DESC");
    $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impact Stories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background: #ff6f61;
            color: #fff;
        }
        .btn {
            padding: 5px 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-edit {
            background: #3498db;
        }
        .btn-delete {
            background: #e74c3c;
        }
        .btn-new {
            display: inline-block;
            margin-top: 10px;
            background: #ff4d6d;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Impact Stories</h1>
        <a href="edit_impact_stories.php" class="btn btn-new">Add New Story</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stories)) : ?>
                    <?php foreach ($stories as $story) : ?>
                        <tr>
                            <td><?= htmlspecialchars($story['id']); ?></td>
                            <td><?= htmlspecialchars($story['title']); ?></td>
                            <td><?= htmlspecialchars(substr($story['description'], 0, 50)) . '...'; ?></td>
                            <td><img src="<?= htmlspecialchars($story['image_url']); ?>" alt="Image" style="width: 50px; height: 50px;"></td>
                            <td>
                                <a href="edit_impact_stories.php?id=<?= $story['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="delete_story.php?id=<?= $story['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this story?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">No stories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>