<?php include 'db.php'; ?>
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    
    // Validate campaign_id
    if (!isset($_POST['campaign_id']) || !is_numeric($_POST['campaign_id'])) {
        $errors[] = "Invalid campaign";
    } else {
        $campaign_id = (int)$_POST['campaign_id'];
        
        // Verify campaign exists
        $stmt = $conn->prepare("SELECT id FROM campaigns WHERE id = ?");
        $stmt->bind_param("i", $campaign_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) {
            $errors[] = "Campaign not found";
        }
    }
    
    // Validate donor name
    $donor_name = trim($_POST['donor_name'] ?? '');
    if (empty($donor_name)) {
        $errors[] = "Please enter your name";
    } elseif (strlen($donor_name) > 255) {
        $errors[] = "Name is too long";
    }
    
    // Validate amount
    $amount = filter_var($_POST['amount'] ?? '', FILTER_VALIDATE_FLOAT);
    if ($amount === false || $amount <= 0) {
        $errors[] = "Please enter a valid donation amount";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO donations (campaign_id, donor_name, amount) VALUES (?, ?, ?)");
            $stmt->bind_param("isd", $campaign_id, $donor_name, $amount);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Thank you for your donation!";
                header("Location: campaign_detail.php?id=$campaign_id");
                exit();
            } else {
                throw new Exception("Error processing donation");
            }
        } catch (Exception $e) {
            $errors[] = "An error occurred while processing your donation. Please try again.";
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['error_messages'] = $errors;
        $_SESSION['form_data'] = [
            'donor_name' => $donor_name,
            'amount' => $amount
        ];
        header("Location: campaign_detail.php?id=$campaign_id");
        exit();
    }
} else {
    // If someone tries to access this page directly
    header("Location: campaigns.php");
    exit();
}
?>
