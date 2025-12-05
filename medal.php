<?php
/**
 * DarkEden Web Panel - Gold Medal Management
 */
require_once 'config.php';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$playerid = $_SESSION['playerid'];
$db = getDB();

// Get player's gold medals
if ($db && isDBConnected()) {
    try {
        $stmt = $db->prepare("SELECT SUM(Amount) as total FROM GoldMedal WHERE PlayerID = ?");
        $stmt->bind_param("s", $playerid);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_medals = $result->fetch_assoc()['total'] ?? 0;
    } catch (Exception $e) {
        $total_medals = 0;
    }
} else {
    // VIEW MODE: Mock data
    $total_medals = 25;
}
$total_points = $total_medals * $medalvalue;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Medals - DarkEden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <h1>Gold Medal Management</h1>
    
    <div class="section">
        <div class="stat-card">
            <h3>Total Gold Medals</h3>
            <div class="number"><?php echo number_format($total_medals); ?></div>
        </div>
        
        <div class="stat-card">
            <h3>Shop Points Value</h3>
            <div class="number"><?php echo number_format($total_points); ?></div>
            <p style="font-size: 0.8em; color: #666; margin-top: 5px;">
                (<?php echo $medalvalue; ?> points per medal)
            </p>
        </div>
    </div>
    
    <div class="section">
        <h2>Medal History</h2>
        <?php
        if ($db && isDBConnected()) {
            try {
                $stmt = $db->prepare("SELECT * FROM GoldMedal WHERE PlayerID = ? ORDER BY Date DESC LIMIT 20");
                $stmt->bind_param("s", $playerid);
                $stmt->execute();
                $medals = $stmt->get_result();
                $has_medals = $medals && $medals->num_rows > 0;
            } catch (Exception $e) {
                $medals = false;
                $has_medals = false;
            }
        } else {
            // VIEW MODE: Mock medal history
            $medals = false;
            $has_medals = false;
        }
        
        if ($has_medals):
        ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($medal = $medals->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($medal['Date'] ?? 'N/A'); ?></td>
                            <td><?php echo number_format($medal['Amount'] ?? 0); ?></td>
                            <td><?php echo number_format(($medal['Amount'] ?? 0) * $medalvalue); ?> points</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No gold medals found.</p>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>Use Points</h2>
        <p>
            <a href="shop/index.php" class="btn">Item Shop</a>
            <a href="jobchange/index.php" class="btn">Job Change</a>
        </p>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>

