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

    // Check if the ID is provided
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];

        // Delete story
        $stmt = $pdo->prepare("DELETE FROM impact_us WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Redirect back to the impact stories page
        header("Location: impact_stories.php");
        exit;
    } else {
        echo "Story ID not provided.";
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>