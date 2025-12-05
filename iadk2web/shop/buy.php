<?php
/**
 * DarkEden Web Panel - Buy Item
 */
require_once '../config.php';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../login.php');
    exit;
}

$playerid = $_SESSION['playerid'];
$itemid = intval($_GET['item'] ?? 0);

if ($itemid == 0) {
    header('Location: index.php');
    exit;
}

$db = getDB();

// Get item info
$stmt = $db->prepare("SELECT * FROM GoodsListInfo WHERE ItemID = ?");
$stmt->bind_param("i", $itemid);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    die('Item not found');
}

// Get player's shop points (from GoldMedal table or similar)
// This is a placeholder - adjust based on your database structure
$stmt = $db->prepare("SELECT SUM(Amount) as points FROM GoldMedal WHERE PlayerID = ?");
$stmt->bind_param("s", $playerid);
$stmt->execute();
$points_result = $stmt->get_result();
$player_points = $points_result->fetch_assoc()['points'] ?? 0;
$player_points = $player_points * $medalvalue; // Convert medals to points

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($player_points >= $item['Price']) {
        // Process purchase - add item to player inventory
        // This is a placeholder - implement based on your database structure
        $success = 'Item purchased successfully!';
    } else {
        $error = 'Not enough shop points!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Item - DarkEden</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    
    <h1>Purchase Item</h1>
    
    <div class="section">
        <p><strong>Your Shop Points:</strong> <?php echo number_format($player_points); ?></p>
        <p><strong>Item:</strong> <?php echo htmlspecialchars($item['ItemName'] ?? 'Unknown'); ?></p>
        <p><strong>Price:</strong> <?php echo number_format($item['Price'] ?? 0); ?> Points</p>
        
        <?php if ($error): ?>
            <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div style="background: #efe; color: #3c3; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php else: ?>
            <form method="POST" action="buy.php?item=<?php echo $itemid; ?>">
                <p>Select character to receive item:</p>
                <select name="charname" required style="padding: 10px; width: 100%; margin: 10px 0;">
                    <?php
                    $stmt = $db->prepare("SELECT Name FROM PlayerCreature WHERE PlayerID = ?");
                    $stmt->bind_param("s", $playerid);
                    $stmt->execute();
                    $chars = $stmt->get_result();
                    while ($char = $chars->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($char['Name']) . '">' . htmlspecialchars($char['Name']) . '</option>';
                    }
                    ?>
                </select>
                <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Purchase</button>
            </form>
        <?php endif; ?>
        
        <p style="margin-top: 20px;"><a href="index.php">Back to Shop</a></p>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>

