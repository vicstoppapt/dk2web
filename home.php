<?php
/**
 * DarkEden Web Panel - Player Home
 */
require_once 'config.php';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$playerid = $_SESSION['playerid'];
$db = getDB();

// Get player characters
$stmt = $db->prepare("SELECT Name, Level, Race, Fame FROM PlayerCreature WHERE PlayerID = ?");
$stmt->bind_param("s", $playerid);
$stmt->execute();
$characters = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - DarkEden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <h1>Welcome, <?php echo htmlspecialchars($playerid); ?>!</h1>
    
    <div class="section">
        <h2>My Characters</h2>
        <?php if ($characters->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Character Name</th>
                        <th>Level</th>
                        <th>Race</th>
                        <th>Fame</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($char = $characters->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($char['Name']); ?></td>
                            <td><?php echo $char['Level']; ?></td>
                            <td><?php echo htmlspecialchars($char['Race']); ?></td>
                            <td><?php echo number_format($char['Fame']); ?></td>
                            <td>
                                <a href="delete_char.php?name=<?php echo urlencode($char['Name']); ?>" 
                                   onclick="return confirm('Are you sure you want to delete this character?');"
                                   style="color: red;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no characters yet.</p>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>Quick Actions</h2>
        <p>
            <a href="shop/index.php" class="btn">Item Shop</a>
            <a href="jobchange/index.php" class="btn">Job Change</a>
            <a href="medal.php" class="btn">Gold Medals</a>
            <a href="downloads.php" class="btn">Downloads</a>
        </p>
    </div>
    
    <div class="section">
        <p><a href="logout.php" style="color: #c33;">Logout</a></p>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>

