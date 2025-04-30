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

// Fetch counts for dashboard metrics
$donations_count = $conn->query("SELECT COUNT(*) AS count FROM donations")->fetch_assoc()['count'];
$payments_count = $conn->query("SELECT COUNT(*) AS count FROM payments")->fetch_assoc()['count'];
$requests_count = $conn->query("SELECT COUNT(*) AS count FROM organization_requests")->fetch_assoc()['count'];
$volunteers_count = $conn->query("SELECT COUNT(*) AS count FROM volunteers")->fetch_assoc()['count'];

// Handle CSV Export
if (isset($_POST['export_csv'])) {
    $filename = "dashboard_report_" . date('Y-m-d') . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // Write CSV data
    $output = fopen("php://output", "w");
    fputcsv($output, ['Metric', 'Count']);
    fputcsv($output, ['Donations', $donations_count]);
    fputcsv($output, ['Payments', $payments_count]);
    fputcsv($output, ['Organization Requests', $requests_count]);
    fputcsv($output, ['Volunteers', $volunteers_count]);
    fclose($output);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard with CSV Export</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f6fa;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2a2a2a;
            margin-bottom: 30px;
        }

        .card-container {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            width: 220px;
            padding: 20px;
            background: #ff4d6d;;
            color: white;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .chart-container {
            margin: 30px 0;
        }

        .export-container {
            text-align: center;
            margin-top: 30px;
        }

        .export-btn {
            padding: 10px 20px;
            background: #ff4d6d;;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .export-btn:hover {
            background: #2a2a2a;
        }

        @media screen and (max-width: 768px) {
            .card-container {
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Community Dashboard</h1>

        <!-- Summary Cards -->
        <div class="card-container">
            <div class="card">Donations: <?php echo $donations_count; ?></div>
            <div class="card">Payments: <?php echo $payments_count; ?></div>
            <div class="card">Requests: <?php echo $requests_count; ?></div>
            <div class="card">Volunteers: <?php echo $volunteers_count; ?></div>
        </div>

        <!-- Charts -->
        <div class="chart-container">
            <canvas id="summaryChart"></canvas>
        </div>

        <!-- Export Button -->
        <div class="export-container">
            <form method="POST" action="">
                <button type="submit" name="export_csv" class="export-btn">Export as CSV</button>
            </form>
        </div>
    </div>

    <script>
        // Chart.js configuration
        const ctx = document.getElementById('summaryChart').getContext('2d');
        const summaryChart = new Chart(ctx, {
            type: 'pie', // Pie chart for better visual comparison
            data: {
                labels: ['Donations', 'Payments', 'Requests', 'Volunteers'],
                datasets: [{
                    label: 'Summary',
                    data: [
                        <?php echo $donations_count; ?>,
                        <?php echo $payments_count; ?>,
                        <?php echo $requests_count; ?>,
                        <?php echo $volunteers_count; ?>
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
