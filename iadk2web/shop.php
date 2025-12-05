<?php
/**
 * DarkEden Web Panel - Item Shop
 */
require_once 'config.php';

$page_title = "Item Shop - DarkEden";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <h1>Item Shop</h1>
    
    <div class="section">
        <h2>Shop Information</h2>
        <p><strong>Gold Medal Value:</strong> <?php echo $medalvalue; ?> Shop Points</p>
        <p><strong>Job Change Price:</strong> <?php echo $jobprice; ?> Shop Points</p>
    </div>
    
    <div class="section">
        <h2>Available Items</h2>
        <p>Shop functionality coming soon...</p>
        <p>This section will allow players to purchase items using shop points.</p>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>

