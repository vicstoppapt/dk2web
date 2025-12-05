<?php
/**
 * DarkEden Web Panel - Job Change
 */
require_once '../config.php';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../login.php');
    exit;
}

$playerid = $_SESSION['playerid'];
$db = getDB();

// Get player characters
$stmt = $db->prepare("SELECT Name, Level, Race FROM PlayerCreature WHERE PlayerID = ?");
$stmt->bind_param("s", $playerid);
$stmt->execute();
$characters = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Change - DarkEden</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    
    <h1>Job Change</h1>
    
    <div class="section">
        <p><strong>Job Change Price:</strong> <?php echo $jobprice; ?> Shop Points</p>
        <p>Select a character to change job:</p>
        
        <?php if ($characters->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Character Name</th>
                        <th>Level</th>
                        <th>Race</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($char = $characters->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($char['Name']); ?></td>
                            <td><?php echo $char['Level']; ?></td>
                            <td><?php echo htmlspecialchars($char['Race']); ?></td>
                            <td><a href="char.php?name=<?php echo urlencode($char['Name']); ?>" class="btn">Change Job</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no characters.</p>
        <?php endif; ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>

