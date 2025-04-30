<?php
// Database connection credentials
$host = 'localhost';
$dbname = 'surplus_to_serve';
$username = 'root';
$password = '';

try {
    // Establish the connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the counts for statistics
    $donations_stmt = $pdo->prepare("SELECT COUNT(*) AS total_donations FROM donations");
    $donations_stmt->execute();
    $total_donations = $donations_stmt->fetch(PDO::FETCH_ASSOC)['total_donations'];

    $payments_stmt = $pdo->prepare("SELECT COUNT(*) AS total_payments FROM payments");
    $payments_stmt->execute();
    $total_payments = $payments_stmt->fetch(PDO::FETCH_ASSOC)['total_payments'];

    $requests_stmt = $pdo->prepare("SELECT COUNT(*) AS total_requests FROM organization_requests");
    $requests_stmt->execute();
    $total_requests = $requests_stmt->fetch(PDO::FETCH_ASSOC)['total_requests'];

    $volunteers_stmt = $pdo->prepare("SELECT COUNT(*) AS total_volunteers FROM volunteers");
    $volunteers_stmt->execute();
    $total_volunteers = $volunteers_stmt->fetch(PDO::FETCH_ASSOC)['total_volunteers'];

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Performance | Admin Panel</title>
    <style>
        /* Base styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: #f4f4f4;
        }
        header {
            background: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        header h1 {
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .stat-card {
            flex: 1 1 calc(25% - 20px);
            background: #f9f9f9;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            background-color: #eaf9ea;
        }
        .stat-card h3 {
            font-size: 2rem;
            margin: 0;
            color: #4CAF50;
        }
        .stat-card p {
            margin: 10px 0 0;
        }
        .chart-container {
            margin-top: 40px;
            text-align: center;
        }
        canvas {
            max-width: 100%;
        }
        footer {
            text-align: center;
            padding: 20px 0;
            background: #4CAF50;
            color: #fff;
            position: relative;
            margin-top: 40px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <h1>Admin Performance Dashboard</h1>
</header>

<div class="container">
    <section class="stats">
        <div class="stat-card">
            <h3><?= htmlspecialchars($total_donations); ?></h3>
            <p>Total Donations</p>
        </div>
        <div class="stat-card">
            <h3><?= htmlspecialchars($total_payments); ?></h3>
            <p>Total Payments</p>
        </div>
        <div class="stat-card">
            <h3><?= htmlspecialchars($total_requests); ?></h3>
            <p>Organization Requests</p>
        </div>
        <div class="stat-card">
            <h3><?= htmlspecialchars($total_volunteers); ?></h3>
            <p>Volunteers</p>
        </div>
    </section>

    <section class="chart-container">
        <h2>Performance Overview</h2>
        <canvas id="performanceChart"></canvas>
    </section>
</div>

<footer>
    <p>&copy; <?= date("Y"); ?> Surplus_to_Serve. All Rights Reserved.</p>
</footer>

<script>
    // Chart.js setup
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Donations', 'Payments', 'Requests', 'Volunteers'],
            datasets: [{
                label: 'Performance Metrics',
                data: [<?= $total_donations; ?>, <?= $total_payments; ?>, <?= $total_requests; ?>, <?= $total_volunteers; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(54, 162, 235, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
    });
</script>

</body>
</html>
