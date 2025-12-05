<?php
/**
 * DarkEden Web Panel - Player Management
 */
require_once 'config.php';

$page_title = "Player Management - DarkEden";
$db = getDB();

// Search functionality
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';
$players = [];

if ($search) {
    $stmt = $db->prepare("SELECT PlayerID, Name, Level, Race, Fame FROM PlayerCreature WHERE Name LIKE ? OR PlayerID LIKE ? LIMIT 50");
    $search_term = "%$search%";
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }
}
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
    
    <h1>Player Management</h1>
    
    <div class="section">
        <form method="GET" action="players.php">
            <input type="text" name="search" placeholder="Search by Player ID or Character Name..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 10px; width: 300px; border-radius: 5px; border: 1px solid #ddd;">
            <button type="submit" class="btn">Search</button>
        </form>
    </div>
    
    <?php if ($search && count($players) > 0): ?>
        <div class="section">
            <h2>Search Results (<?php echo count($players); ?>)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Character Name</th>
                        <th>Level</th>
                        <th>Race</th>
                        <th>Fame</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($players as $player): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($player['PlayerID']); ?></td>
                            <td><?php echo htmlspecialchars($player['Name']); ?></td>
                            <td><?php echo $player['Level']; ?></td>
                            <td><?php echo htmlspecialchars($player['Race']); ?></td>
                            <td><?php echo number_format($player['Fame']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($search): ?>
        <div class="section">
            <p>No players found matching "<?php echo htmlspecialchars($search); ?>"</p>
        </div>
    <?php else: ?>
        <div class="section">
            <p>Enter a search term to find players.</p>
        </div>
    <?php endif; ?>
    
    <?php include 'footer.php'; ?>
</body>
</html>

