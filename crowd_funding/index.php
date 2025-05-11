<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Crowdfunding Platform</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome to Our Crowdfunding Platform</h1>
            <nav>
                <a href="campaigns.php" class="nav-link">View Campaigns</a>
                <a href="create_campaign.php" class="nav-link">Start a Campaign</a>
            </nav>
        </header>

        <main>
            <div class="welcome-section">
                <h2>Make a Difference Today</h2>
                <p>Support amazing projects or start your own campaign to make your dreams a reality.</p>
                
                <div class="cta-buttons">
                    <a href="campaigns.php" class="btn btn-primary">Browse Campaigns</a>
                    <a href="create_campaign.php" class="btn btn-secondary">Start a Campaign</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

