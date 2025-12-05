<?php
/**
 * DarkEden Web Panel - Rankings
 */
require_once 'config.php';

$page_title = "Player Rankings - DarkEden";
$db = getDB();
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
    
    <div class="content">
        <h1>Player Rankings</h1>
        
        <?php
        // Get top Slayers
        $slayer_query = "SELECT Name, Level, Fame FROM PlayerCreature 
                        WHERE Race = 'SLAYER' 
                        ORDER BY Level DESC, Fame DESC 
                        LIMIT " . $slayer_rank_limit;
        $slayers = $db->query($slayer_query);
        
        // Get top Vampires
        $vampire_query = "SELECT Name, Level, Fame FROM PlayerCreature 
                         WHERE Race = 'VAMPIRE' 
                         ORDER BY Level DESC, Fame DESC 
                         LIMIT " . $vampire_rank_limit;
        $vampires = $db->query($vampire_query);
        
        // Get top Ousters
        $ouster_query = "SELECT Name, Level, Fame FROM PlayerCreature 
                        WHERE Race = 'OUSTERS' 
                        ORDER BY Level DESC, Fame DESC 
                        LIMIT " . $ousters_rank_limit;
        $ousters = $db->query($ouster_query);
        ?>
        
        <div class="rankings-container">
            <div class="ranking-section">
                <h2>Top Slayers</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Level</th>
                            <th>Fame</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rank = 1;
                        while ($row = $slayers->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>#" . $rank . "</td>";
                            echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                            echo "<td>" . $row['Level'] . "</td>";
                            echo "<td>" . number_format($row['Fame']) . "</td>";
                            echo "</tr>";
                            $rank++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="ranking-section">
                <h2>Top Vampires</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Level</th>
                            <th>Fame</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rank = 1;
                        while ($row = $vampires->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>#" . $rank . "</td>";
                            echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                            echo "<td>" . $row['Level'] . "</td>";
                            echo "<td>" . number_format($row['Fame']) . "</td>";
                            echo "</tr>";
                            $rank++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="ranking-section">
                <h2>Top Ousters</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Level</th>
                            <th>Fame</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rank = 1;
                        while ($row = $ousters->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>#" . $rank . "</td>";
                            echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                            echo "<td>" . $row['Level'] . "</td>";
                            echo "<td>" . number_format($row['Fame']) . "</td>";
                            echo "</tr>";
                            $rank++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>

