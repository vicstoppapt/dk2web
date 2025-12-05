<?php
/**
 * DarkEden Web Panel - Client Downloads
 */
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloads - DarkEden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <h1>Client Downloads</h1>
    
    <div class="section">
        <h2>Game Client</h2>
        <p>Download the DarkEden game client to play on our server.</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>Client Download</h3>
            <p><strong>Version:</strong> Latest</p>
            <p><strong>Size:</strong> ~500 MB</p>
            <p><a href="down/client.zip" class="btn">Download Client</a></p>
        </div>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>Patches & Updates</h3>
            <p>Check here for game patches and updates.</p>
        </div>
    </div>
    
    <div class="section">
        <h2>Installation Instructions</h2>
        <ol>
            <li>Download the client</li>
            <li>Extract to a folder</li>
            <li>Edit <code>Data/Info/GameClient.inf</code> and set server IP to: <strong><?php echo $_SERVER['SERVER_NAME']; ?></strong></li>
            <li>Run the game!</li>
        </ol>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>

