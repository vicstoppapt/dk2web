<?php
/**
 * DarkEden Web Panel - Job Change for Character
 */
require_once '../config.php';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../login.php');
    exit;
}

$playerid = $_SESSION['playerid'];
$charname = clean_input($_GET['name'] ?? '');

if (empty($charname)) {
    header('Location: index.php');
    exit;
}

$db = getDB();

// Verify character belongs to player
$stmt = $db->prepare("SELECT Name, Level, Race FROM PlayerCreature WHERE Name = ? AND PlayerID = ?");
$stmt->bind_param("ss", $charname, $playerid);
$stmt->execute();
$char = $stmt->get_result()->fetch_assoc();

if (!$char) {
    die('Character not found');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if player has enough shop points
    $stmt = $db->prepare("SELECT SUM(Amount) as points FROM GoldMedal WHERE PlayerID = ?");
    $stmt->bind_param("s", $playerid);
    $stmt->execute();
    $points_result = $stmt->get_result();
    $player_points = $points_result->fetch_assoc()['points'] ?? 0;
    $player_points = $player_points * $medalvalue;
    
    if ($player_points >= $jobprice) {
        // Deduct points and change job
        // This is a placeholder - implement job change logic based on your server
        $success = 'Job change successful!';
    } else {
        $error = 'Not enough shop points! Need ' . $jobprice . ' points.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Change - <?php echo htmlspecialchars($charname); ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    
    <h1>Job Change: <?php echo htmlspecialchars($charname); ?></h1>
    
    <div class="section">
        <p><strong>Character:</strong> <?php echo htmlspecialchars($char['Name']); ?></p>
        <p><strong>Level:</strong> <?php echo $char['Level']; ?></p>
        <p><strong>Race:</strong> <?php echo htmlspecialchars($char['Race']); ?></p>
        <p><strong>Price:</strong> <?php echo $jobprice; ?> Shop Points</p>
        
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
            <form method="POST" action="char.php?name=<?php echo urlencode($charname); ?>">
                <p>Are you sure you want to change job for this character?</p>
                <button type="submit" class="btn">Confirm Job Change</button>
            </form>
        <?php endif; ?>
        
        <p style="margin-top: 20px;"><a href="index.php">Back</a></p>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>

