<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'surplus_to_serve');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Section 1: Total Counts
$donor_count = $conn->query("SELECT COUNT(*) AS count FROM donors")->fetch_assoc()['count'];
$volunteer_count = $conn->query("SELECT COUNT(*) AS count FROM volunteers")->fetch_assoc()['count'];
$organization_count = $conn->query("SELECT COUNT(*) AS count FROM organizations")->fetch_assoc()['count'];

// Section 2: Pending Requests
$pending_requests = $conn->query("SELECT COUNT(*) AS count FROM organization_requests WHERE status = 'Pending'")->fetch_assoc()['count'];

// Section 3: Total Donations and Payments
$total_donations = $conn->query("SELECT COUNT(*) AS count FROM donations")->fetch_assoc()['count'];
$total_payments = $conn->query("SELECT SUM(amount) AS total FROM payments")->fetch_assoc()['total'] ?? 0;

// Section 4: Volunteer Tasks
$tasks_accepted = $conn->query("SELECT COUNT(*) AS count FROM tasks WHERE status = 'accepted'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SurplusToServe Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
        }

        .header {
            background-color: #ff4d6d;
            color: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 250px;
            background-color: #2a2a2a;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .sidebar h3, .dropdown-btn {
            padding: 15px;
            cursor: pointer;
            text-align: left;
            background-color: #2a2a2a;
            color: white;
            border: none;
            width: 100%;
            outline: none;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sidebar h3:hover, .dropdown-btn:hover {
            background-color: #ff4d6d;
            transform: translateX(10px);
        }

        .dropdown-container {
            display: none;
            background-color: #3a4b5c;
            padding-left: 15px;
            overflow: hidden;
            animation: fadeIn 0.3s ease-in-out;
        }

        .dropdown-container a {
            padding: 10px;
            display: block;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .dropdown-container a:hover {
            background-color: #ff4d6d;
            transform: translateX(10px);
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .btn {
            background-color: #ff4d6d;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #2a2a2a;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            transition: box-shadow 0.3s ease;
        }

        table:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #ff4d6d;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            text-align: center;
        }

        .dashboard-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card h2 {
            margin-bottom: 15px;
            color: #ff4d6d;
        }

        .dashboard-card p {
            font-size: 1.2em;
            margin: 5px 0;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Admin Dashboard - SurplusToServe</h1>
    </div>

    <div class="sidebar">
        <h3>Navigation</h3>

        <button class="dropdown-btn">User Management</button>
        <div class="dropdown-container">
            <a href="manage_users.php">Manage Users</a>
            <a href="user_deactivate.html">Deactivate Users</a>
        </div>

        <button class="dropdown-btn">Donation & Request Management</button>
        <div class="dropdown-container">
            <a href="edit_donations.php">View Donations</a>
            <a href="approve_request.php">Approve Requests</a>
        </div>

        <button class="dropdown-btn">Volunteer Management</button>
        <div class="dropdown-container">
            <a href="admin_tasks.php">Assign Tasks</a>
            <a href="edit_volunteer_dashboard.php">Track Volunteers</a>
        </div>

        <button class="dropdown-btn">Content Management</button>
        <div class="dropdown-container">
            <a href="edit_team_and_journey.php">Edit Content</a>
            <a href="impact_stories.php">Manage Success Stories</a>
        </div>

        <button class="dropdown-btn">Reports & Analytics</button>
        <div class="dropdown-container">
            <a href="generate_reports.php">Generate Reports</a>
            <a href="track_performance.php">Track Performance</a>
        </div>

        <button class="dropdown-btn">Notifications & Alerts</button>
        <div class="dropdown-container">
            <a href="post_alerts.html">Create Alerts</a>
            <a href="admin_notifications.php">Manage Notifications</a>
        </div>

        <button class="dropdown-btn">Financial Management</button>
        <div class="dropdown-container">
            <a href="view_payments.php">View Financial Data</a>
            <a href="manage_expenses.php">Manage expenses</a>
        </div>

        <a href="admin_logout.php" class="btn">Logout</a>
    </div>
    <div class="main-content">
    <div class="dashboard-section">
        <!-- Section 1: Total Counts -->
        <div class="dashboard-card">
            <h2>Total Counts</h2>
            <p><strong>Donors:</strong> <?php echo $donor_count; ?></p>
            <p><strong>Volunteers:</strong> <?php echo $volunteer_count; ?></p>
            <p><strong>Organizations:</strong> <?php echo $organization_count; ?></p>
        </div>

        <!-- Section 2: Pending Requests -->
        <div class="dashboard-card">
            <h2>Pending Requests</h2>
            <p><strong>Pending Requests:</strong> <?php echo $pending_requests; ?></p>
        </div>

        <!-- Section 3: Donations and Payments -->
        <div class="dashboard-card">
            <h2>Donations & Payments</h2>
            <p><strong>Total Donations:</strong> <?php echo $total_donations; ?></p>
            <p><strong>Total Payments:</strong> ₹<?php echo number_format($total_payments, 2); ?></p>
        </div>

        <!-- Section 4: Tasks Accepted -->
        <div class="dashboard-card">
            <h2>Volunteer Tasks</h2>
            <p><strong>Tasks Accepted:</strong> <?php echo $tasks_accepted; ?></p>
        </div>
    </div>
</div>
<script>
    // JavaScript to handle dropdown functionality
    document.querySelectorAll(".dropdown-btn").forEach(button => {
        button.addEventListener("click", function () {
            this.classList.toggle("active");
            const dropdownContent = this.nextElementSibling;
            dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
        });
    });
</script>
</html>