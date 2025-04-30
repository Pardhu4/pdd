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

    // Fetch the team members
    $team_stmt = $pdo->prepare("SELECT * FROM team");
    $team_stmt->execute();
    $team_members = $team_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the journey milestones
    $journey_stmt = $pdo->prepare("SELECT * FROM journey");
    $journey_stmt->execute();
    $journey_milestones = $journey_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle update, add, or delete requests for team members
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['update_team'])) {
            // Handle team member update
            $id = $_POST['id'];
            $name = $_POST['name'];
            $position = $_POST['position'];

            // Handle the image upload (only if a new image is provided)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image'];
                $uploadDir = 'uploads/team_images/';
                $imageName = time() . '-' . basename($image['name']);
                $uploadFile = $uploadDir . $imageName;

                // Check if the file is an image
                if (getimagesize($image['tmp_name']) !== false) {
                    // Ensure the directory exists
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Move the uploaded image to the directory
                    if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                        $imageUrl = $uploadFile;
                    } else {
                        echo "Sorry, there was an error uploading your image.";
                        exit;
                    }
                } else {
                    echo "File is not an image.";
                    exit;
                }
            } else {
                // If no new image is uploaded, use the existing image URL
                $imageUrl = isset($_POST['existing_image_url']) ? $_POST['existing_image_url'] : '';
            }

            // Update the team member in the database
            $update_stmt = $pdo->prepare("UPDATE team SET name = ?, position = ?, image_url = ? WHERE id = ?");
            $update_stmt->execute([$name, $position, $imageUrl, $id]);

            $success_message = "Team member updated successfully!";
        } elseif (isset($_POST['delete_team'])) {
            // Handle team member delete
            $id = $_POST['id'];
            $delete_stmt = $pdo->prepare("DELETE FROM team WHERE id = ?");
            $delete_stmt->execute([$id]);

            $success_message = "Team member deleted successfully!";
        } elseif (isset($_POST['add_team'])) {
            // Handle new team member addition
            $name = $_POST['name'];
            $position = $_POST['position'];

            // Handle the image upload (only if a new image is provided)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image'];
                $uploadDir = 'uploads/team_images/';
                $imageName = time() . '-' . basename($image['name']);
                $uploadFile = $uploadDir . $imageName;

                // Check if the file is an image
                if (getimagesize($image['tmp_name']) !== false) {
                    // Ensure the directory exists
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Move the uploaded image to the directory
                    if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                        $imageUrl = $uploadFile;
                    } else {
                        echo "Sorry, there was an error uploading your image.";
                        exit;
                    }
                } else {
                    echo "File is not an image.";
                    exit;
                }
            } else {
                // If no image is uploaded, you can handle the case (e.g., by not setting $imageUrl or using a placeholder)
                $imageUrl = ''; // Or set a default placeholder image if desired
            }

            // Insert the new team member into the database
            $insert_stmt = $pdo->prepare("INSERT INTO team (name, position, image_url) VALUES (?, ?, ?)");
            $insert_stmt->execute([$name, $position, $imageUrl]);

            $success_message = "New team member added successfully!";
        }
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
    <title>Edit Team and Journey</title>
    <a href="admin.php" class="back-button">Back to Admin Panel</a>    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background: #ff6f61;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .section {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            text-align: center;
            color: #ff6f61;
        }
        .team-grid, .journey-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .item {
            width: 45%;
            padding: 10px;
            background: #fafafa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .item:hover {
            transform: scale(1.05);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 1rem;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 20px;
            background-color: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2a2a2a;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #2a2a2a;
        }
        .add-btn {
            background-color: #ff4d6d;
        }
        .add-btn:hover {
            background-color: #2a2a2a;
        }
        .success-btn {
            background-color: #008CBA;
        }
        .success-btn:hover {
            background-color: #2a2a2a;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
        }
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 10px;
            top: 10px;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Team and Journey</h1>
    </header>

    <div class="container">

        <!-- Success Message -->
        <?php if (isset($success_message)): ?>
            <div class="alert">
                <p><?= $success_message ?></p>
            </div>
        <?php endif; ?>

        <!-- Edit Team Section -->
        <div class="section">
            <h2>Team Members</h2>
            <div class="team-grid">
                <?php foreach ($team_members as $member) : ?>
                    <div class="item">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $member['id'] ?>">
                            <div class="form-group">
                                <label for="name_<?= $member['id'] ?>">Name</label>
                                <input type="text" id="name_<?= $member['id'] ?>" name="name" value="<?= htmlspecialchars($member['name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="position_<?= $member['id'] ?>">Position</label>
                                <input type="text" id="position_<?= $member['id'] ?>" name="position" value="<?= htmlspecialchars($member['position']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="image_<?= $member['id'] ?>">Upload Image</label>
                                <input type="file" id="image_<?= $member['id'] ?>" name="image" accept="image/*">
                                <input type="hidden" name="existing_image_url" value="<?= $member['image_url'] ?>">
                            </div>
                            <button type="submit" name="update_team" class="btn">Update Member</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $member['id'] ?>">
                            <button type="submit" name="delete_team" class="btn delete-btn">Delete Member</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <form method="POST" enctype="multipart/form-data">
                    <h3>Add New Member</h3>
                    <div class="form-group">
                        <label for="new_name">Name</label>
                        <input type="text" id="new_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="new_position">Position</label>
                        <input type="text" id="new_position" name="position" required>
                    </div>
                    <div class="form-group">
                        <label for="new_image">Upload Image</label>
                        <input type="file" id="new_image" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" name="add_team" class="btn add-btn">Add Member</button>
                </form>
            </div>
        </div>

        <!-- Edit Journey Section -->
        <div class="section">
            <h2>Journey</h2>
            <div class="journey-grid">
                <?php foreach ($journey_milestones as $milestone) : ?>
                    <div class="item">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $milestone['id'] ?>">
                            <div class="form-group">
                                <label for="milestone_<?= $milestone['id'] ?>">Milestone</label>
                                <textarea name="milestone" id="milestone_<?= $milestone['id'] ?>" required><?= htmlspecialchars($milestone['milestone']) ?></textarea>
                            </div>
                            <button type="submit" name="update_journey" class="btn">Update Milestone</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</body>
</html>