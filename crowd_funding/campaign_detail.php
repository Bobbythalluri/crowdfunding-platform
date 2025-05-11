<?php include 'db.php'; ?>
<?php
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: campaigns.php");
    exit();
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM campaigns WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$campaign = $result->fetch_assoc();

if (!$campaign) {
    header("Location: campaigns.php");
    exit();
}

// Get total donations
$stmt = $conn->prepare("SELECT SUM(amount) as total FROM donations WHERE campaign_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$donations_result = $stmt->get_result();
$total_donations = $donations_result->fetch_assoc()['total'] ?? 0;

// Calculate progress
$progress = ($total_donations / $campaign['goal_amount']) * 100;
$progress = min($progress, 100); // Cap at 100%

// Get form data from session if exists
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($campaign['title']); ?> - Crowdfunding Platform</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo htmlspecialchars($campaign['title']); ?></h1>
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="campaigns.php" class="nav-link">View Campaigns</a>
            </nav>
        </header>

        <main>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success-message">
                    <?php 
                    echo htmlspecialchars($_SESSION['success_message']);
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_messages'])): ?>
                <div class="error-messages">
                    <?php foreach ($_SESSION['error_messages'] as $error): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['error_messages']); ?>
                </div>
            <?php endif; ?>

            <div class="campaign-detail">
                <div class="campaign-info">
                    <p class="description"><?php echo nl2br(htmlspecialchars($campaign['description'])); ?></p>
                    
                    <div class="progress-container">
                        <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                    
                    <div class="campaign-stats">
                        <div class="stat">
                            <span class="label">Goal:</span>
                            <span class="value">$<?php echo number_format($campaign['goal_amount'], 2); ?></span>
                        </div>
                        <div class="stat">
                            <span class="label">Raised:</span>
                            <span class="value">$<?php echo number_format($total_donations, 2); ?></span>
                        </div>
                        <div class="stat">
                            <span class="label">Progress:</span>
                            <span class="value"><?php echo number_format($progress, 1); ?>%</span>
                        </div>
                    </div>
                </div>

                <div class="donation-form">
                    <h2>Make a Donation</h2>
                    <form method="post" action="process_donation.php">
                        <input type="hidden" name="campaign_id" value="<?php echo $id; ?>">
                        
                        <div class="form-group">
                            <label for="donor_name">Your Name:</label>
                            <input type="text" id="donor_name" name="donor_name" required
                                   value="<?php echo htmlspecialchars($form_data['donor_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="amount">Amount ($):</label>
                            <input type="number" id="amount" name="amount" required min="1" step="0.01"
                                   value="<?php echo htmlspecialchars($form_data['amount'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <input type="submit" value="Donate Now" class="btn">
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
