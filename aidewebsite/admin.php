<?php
/**
 * DarkEden Web Panel - Admin Panel
 * TODO: Add authentication
 */
require_once 'config.php';

$page_title = "Admin Panel - DarkEden";
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
    
    <h1>Admin Panel</h1>
    
    <div class="section">
        <h2>⚠️ Security Notice</h2>
        <p style="color: red; font-weight: bold;">
            This admin panel is not yet protected. Please implement authentication before using in production!
        </p>
    </div>
    
    <div class="section">
        <h2>Server Management</h2>
        <p>Admin functions coming soon...</p>
        <ul>
            <li>Player management</li>
            <li>Item management</li>
            <li>Server configuration</li>
            <li>Database tools</li>
        </ul>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>

