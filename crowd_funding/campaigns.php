<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>All Campaigns</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <header>
            <h1>All Campaigns</h1>
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="create_campaign.php" class="nav-link">Start a Campaign</a>
            </nav>
        </header>

        <main>
            <?php
            $sql = "SELECT * FROM campaigns ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0): ?>
                <div class="campaigns-grid">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="campaign-card">
                            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="description"><?php echo htmlspecialchars(substr($row['description'], 0, 150)) . '...'; ?></p>
                            <p class="goal">Goal: $<?php echo number_format($row['goal_amount'], 2); ?></p>
                            <a href="campaign_detail.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-campaigns">No campaigns found. Be the first to <a href="create_campaign.php">start a campaign</a>!</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
