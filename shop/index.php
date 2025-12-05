<?php
/**
 * DarkEden Web Panel - Item Shop
 */
require_once '../config.php';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../login.php');
    exit;
}

$playerid = $_SESSION['playerid'];
$db = getDB();

// Get shop items (adjust table name based on your database)
// Original used GoodsListInfo table
if ($db && isDBConnected()) {
    try {
        $items = $db->query("SELECT * FROM GoodsListInfo LIMIT 50");
    } catch (Exception $e) {
        $items = false;
    }
} else {
    // VIEW MODE: Mock shop items
    $items = false; // Will show "No items available" message
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Shop - DarkEden</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    
    <h1>Item Shop</h1>
    
    <div class="section">
        <p><strong>Gold Medal Value:</strong> <?php echo $medalvalue; ?> Shop Points</p>
        <p><a href="buy.php" class="btn">Purchase Items</a></p>
    </div>
    
    <div class="section">
        <h2>Available Items</h2>
        <?php if ($items && $items->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['ItemName'] ?? 'Unknown'); ?></td>
                            <td><?php echo number_format($item['Price'] ?? 0); ?> Points</td>
                            <td><a href="buy.php?item=<?php echo $item['ItemID'] ?? ''; ?>">Buy</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No items available in shop. Please configure GoodsListInfo table.</p>
        <?php endif; ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>

