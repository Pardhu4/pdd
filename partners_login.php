<?php
session_start();
$host = 'localhost';
$dbname = 'surplus_to_serve';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $partner_id = $_POST['partner_id'];
        $store_name = $_POST['store_name'];
        $password = $_POST['password'];

        // Query to fetch partner details using partner_id and store_name
        $query = "SELECT * FROM partners WHERE partner_id = :partner_id AND name = :store_name";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':partner_id' => $partner_id,
            ':store_name' => $store_name
        ]);

        $partner = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password and proceed
        if ($partner && password_verify($password, $partner['password'])) {
            $_SESSION['partner_id'] = $partner['partner_id']; // Store Partner ID in session
            $_SESSION['store_name'] = $partner['name'];       // Store Store Name in session
            header("Location: partners_dashboard.php");       // Redirect to dashboard
            exit;
        } else {
            echo "<script>
                    alert('Invalid Partner ID, Store Name, or Password. Please try again.');
                    window.location.href = 'partners_login.html';
                  </script>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
