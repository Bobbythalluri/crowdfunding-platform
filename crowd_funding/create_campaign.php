<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Campaign</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <header>
            <h1>Start a New Campaign</h1>
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="campaigns.php" class="nav-link">View Campaigns</a>
            </nav>
        </header>

        <main>
            <?php
            if (isset($_POST['submit'])) {
                $title = trim($_POST['title']);
                $description = trim($_POST['description']);
                $goal = floatval($_POST['goal']);

                $errors = [];
                
                if (empty($title)) {
                    $errors[] = "Title is required";
                }
                if (empty($description)) {
                    $errors[] = "Description is required";
                }
                if ($goal <= 0) {
                    $errors[] = "Goal amount must be greater than 0";
                }

                if (empty($errors)) {
                    $stmt = $conn->prepare("INSERT INTO campaigns (title, description, goal_amount) VALUES (?, ?, ?)");
                    $stmt->bind_param("ssd", $title, $description, $goal);
                    
                    if ($stmt->execute()) {
                        header("Location: campaigns.php");
                        exit();
                    } else {
                        $errors[] = "Error creating campaign: " . $conn->error;
                    }
                }
            }
            ?>

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" class="campaign-form">
                <div class="form-group">
                    <label for="title">Campaign Title:</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required rows="6"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="goal">Goal Amount ($):</label>
                    <input type="number" id="goal" name="goal" required min="1" step="0.01"
                           value="<?php echo isset($_POST['goal']) ? htmlspecialchars($_POST['goal']) : ''; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" value="Create Campaign" class="btn">
                </div>
            </form>
        </main>
    </div>
</body>
</html>
