<?php
// Database configuration
$host = 'localhost';
$dbname = 'surplus_to_serve';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert partner data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

        // Generate a unique Partner ID (e.g., HOT-123456 or GRO-654321)
        $prefix = strtoupper(substr($category, 0, 3)); // Prefix based on category
        $partner_id = $prefix . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT); // Random ID with padding

        // Insert partner data into the table
        $query = "INSERT INTO partners (partner_id, name, category, address, contact, password) 
                  VALUES (:partner_id, :name, :category, :address, :contact, :password)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':partner_id' => $partner_id,
            ':name' => $name,
            ':category' => $category,
            ':address' => $address,
            ':contact' => $contact,
            ':password' => $passwordHash
        ]);

        // Show the Partner ID in a popup and redirect to login page
        echo "
            <script>
                alert('Signup successful! Your Partner ID is: $partner_id');
                window.location.href = 'partners_login.html';
            </script>
        ";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
