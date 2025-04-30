<?php
session_start();

// Predefined admin credentials
$admins = [
    'admin1' => 'password1', // Replace with secure passwords
    'admin2' => 'password2',
    'admin3' => 'password3',
    'pardhusrav'=> 'pardhusrav@143'
];

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    // Check if admin_id exists and password matches
    if (array_key_exists($admin_id, $admins) && $admins[$admin_id] === $password) {
        // Set session for authenticated admin
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin_id;

        // Redirect to admin.php
        header("Location: admin.php");
        exit;
    } else {
        // Invalid credentials
        echo "Invalid Admin ID or Password.";
    }
}
?>