<?php
// Database connection credentials
$host = 'localhost';
$dbname = 'surplus_to_serve';
$username = 'root';
$password = '';

$message = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $story = ['id' => '', 'title' => '', 'description' => '', 'image_url' => ''];

    // Check if ID is provided for editing
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM impact_us WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $story = $stmt->fetch(PDO::FETCH_ASSOC) ?: $story;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image_url = $_POST['image_url'];

        if ($id) {
            // Update existing story
            $stmt = $pdo->prepare("UPDATE impact_us SET title = :title, description = :description, image_url = :image_url WHERE id = :id");
            $stmt->execute([
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'image_url' => $image_url
            ]);
        } else {
            // Insert new story
            $stmt = $pdo->prepare("INSERT INTO impact_us (title, description, image_url) VALUES (:title, :description, :image_url)");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'image_url' => $image_url
            ]);
        }

        // Redirect to impact_stories.php
        header("Location: impact_stories.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Impact Story</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Impact Story</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($story['id']); ?>">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($story['title']); ?>" required>
            <label>Description:</label>
            <textarea name="description" rows="5" required><?= htmlspecialchars($story['description']); ?></textarea>
            <label>Image URL:</label>
            <input type="url" name="image_url" value="<?= htmlspecialchars($story['image_url']); ?>" required>
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>