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

    // Fetch the impact stories
    $stmt = $pdo->prepare("SELECT * FROM impact_us");
    $stmt->execute();
    $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the team members
    $team_stmt = $pdo->prepare("SELECT * FROM team");
    $team_stmt->execute();
    $team_members = $team_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the journey milestones
    $journey_stmt = $pdo->prepare("SELECT * FROM journey");
    $journey_stmt->execute();
    $journey_milestones = $journey_stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>About Us | SurplusToServe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }
        header {
            background: url('index_bg.jpg') no-repeat center center/cover;
            height: 60vh;
            color: #fff;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        header h1 {
            font-size: 3rem;
            margin: 0;
        }
        header p {
            font-size: 1.5rem;
            margin-top: 1rem;
        }
        header a {
            margin-top: 20px;
            background: #ff6f61;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
        }
        section {
            padding: 20px;
        }
        .mission {
            background: #f9f9f9;
            padding: 40px;
            text-align: center;
        }
        .mission h2 {
            font-size: 2rem;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .stats div {
            text-align: center;
        }
        .impact-stories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .story {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .team {
            text-align: center;
        }
        .team-grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .team-member {
            margin: 10px;
            text-align: center;
        }
        .timeline {
            background: #eef6f7;
            padding: 40px;
        }
        .timeline div {
            margin-bottom: 20px;
        }
        .get-involved {
            text-align: center;
            background: #ff6f61;
            color: #fff;
            padding: 40px;
        }
        .get-involved a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px 20px;
            border: 1px solid #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<header>
<button onclick="goBack()" style="background-color: #ff4d6d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Go Back</button>
    <h1>About Us</h1>
    <p>Turning Surplus into Service: Fighting Hunger, Reducing Waste, Changing Lives</p>
    <a href="#get-involved">Join Us Today</a>
</header>

<section class="mission">
    <h2>Our Mission</h2>
    <p>To connect surplus food and essential items with those in need, reducing waste and building stronger communities.</p>
    <div class="stats">
        <div>
            <h3><?= htmlspecialchars($total_donations); ?></h3>
            <p>Donations Made</p>
        </div>
        <div>
            <h3><?= htmlspecialchars($total_payments); ?></h3>
            <p>Payments Made</p>
        </div>
        <div>
            <h3><?= htmlspecialchars($total_requests); ?></h3>
            <p>Organization Requests Received</p>
        </div>
        <div>
            <h3><?= htmlspecialchars($total_volunteers); ?></h3>
            <p>Volunteers in Our Community</p>
        </div>
    </div>
</section>

<section class="impact-stories">
    <h2>Impact Stories</h2>
    <?php if (!empty($stories)) : ?>
        <?php foreach ($stories as $story) : ?>
            <div class="story">
                <h3><?= htmlspecialchars($story['title']); ?></h3>
                <p><?= htmlspecialchars($story['description']); ?></p>
                <img src="<?= htmlspecialchars($story['image_url']); ?>" alt="Story Image" style="max-width: 100%; border-radius: 5px;">
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No impact stories available yet.</p>
    <?php endif; ?>
</section>

<section class="team">
    <h2>Meet Our Team</h2>
    <div class="team-grid">
        <?php if (!empty($team_members)) : ?>
            <?php foreach ($team_members as $member) : ?>
                <div class="team-member">
                    <img src="<?= htmlspecialchars($member['image_url']); ?>" alt="Team Member" style="border-radius: 50%; width: 100px;">
                    <h3><?= htmlspecialchars($member['name']); ?></h3>
                    <p><?= htmlspecialchars($member['position']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No team members available.</p>
        <?php endif; ?>
    </div>
</section>

<section class="timeline">
    <h2>Our Journey</h2>
    <?php if (!empty($journey_milestones)) : ?>
        <?php foreach ($journey_milestones as $milestone) : ?>
            <div>
                <h3><?= htmlspecialchars($milestone['year']); ?></h3>
                <p><?= htmlspecialchars($milestone['milestone']); ?></p>
                <p><?= htmlspecialchars($milestone['description']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No journey milestones available.</p>
    <?php endif; ?>
</section>

<section id="get-involved" class="get-involved">
    <h2>Get Involved</h2>
    <p>Join us in making a difference. Volunteer, donate, or partner with us today!</p>
    <a href="volunteer_signup.html">Volunteer</a>
    <a href="signup.html">Donate</a>
</section>
</body>
<script> 
    function goBack() {
    if (document.referrer !== "") {
        window.history.back();
    } else {
        window.location.href = "your-default-url.html"; // Replace with your fallback URL
    }
}

</script>
</html>
