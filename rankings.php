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
        // VIEW MODE: Use mock data if database not available
        $slayers_result = false;
        $vampires_result = false;
        $ousters_result = false;
        $use_mock_data = false;
        
        if (!$db || !isDBConnected()) {
            $use_mock_data = true;
            // Mock ranking data
            $slayers_mock = [
                ['Name' => 'SlayerHero', 'Level' => 85, 'Fame' => 125000],
                ['Name' => 'BladeMaster', 'Level' => 82, 'Fame' => 118000],
                ['Name' => 'WarriorKing', 'Level' => 80, 'Fame' => 110000],
                ['Name' => 'SteelSlayer', 'Level' => 78, 'Fame' => 105000],
                ['Name' => 'IronFist', 'Level' => 75, 'Fame' => 98000]
            ];
            $vampires_mock = [
                ['Name' => 'VampireLord', 'Level' => 88, 'Fame' => 135000],
                ['Name' => 'BloodMaster', 'Level' => 85, 'Fame' => 128000],
                ['Name' => 'NightHunter', 'Level' => 83, 'Fame' => 120000],
                ['Name' => 'DarkPrince', 'Level' => 81, 'Fame' => 115000],
                ['Name' => 'ShadowKnight', 'Level' => 79, 'Fame' => 108000]
            ];
            $ousters_mock = [
                ['Name' => 'OusterElite', 'Level' => 87, 'Fame' => 130000],
                ['Name' => 'MagicWielder', 'Level' => 84, 'Fame' => 122000],
                ['Name' => 'SpellMaster', 'Level' => 82, 'Fame' => 118000],
                ['Name' => 'ArcaneLord', 'Level' => 80, 'Fame' => 112000],
                ['Name' => 'MysticSage', 'Level' => 77, 'Fame' => 102000]
            ];
        } else {
            try {
                // Get top Slayers
                $slayer_query = "SELECT Name, Level, Fame FROM PlayerCreature 
                                WHERE Race = 'SLAYER' 
                                ORDER BY Level DESC, Fame DESC 
                                LIMIT " . (int)$slayer_rank_limit;
                $slayers_result = $db->query($slayer_query);
                
                // Get top Vampires
                $vampire_query = "SELECT Name, Level, Fame FROM PlayerCreature 
                                 WHERE Race = 'VAMPIRE' 
                                 ORDER BY Level DESC, Fame DESC 
                                 LIMIT " . (int)$vampire_rank_limit;
                $vampires_result = $db->query($vampire_query);
                
                // Get top Ousters
                $ouster_query = "SELECT Name, Level, Fame FROM PlayerCreature 
                                WHERE Race = 'OUSTERS' 
                                ORDER BY Level DESC, Fame DESC 
                                LIMIT " . (int)$ousters_rank_limit;
                $ousters_result = $db->query($ouster_query);
            } catch (Exception $e) {
                // Fallback to empty results
                $slayers_result = false;
                $vampires_result = false;
                $ousters_result = false;
            }
        }
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
                        if ($use_mock_data && isset($slayers_mock)) {
                            foreach ($slayers_mock as $row) {
                                echo "<tr>";
                                echo "<td>#" . $rank . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . $row['Level'] . "</td>";
                                echo "<td>" . number_format($row['Fame']) . "</td>";
                                echo "</tr>";
                                $rank++;
                            }
                        } elseif ($slayers_result && is_object($slayers_result) && method_exists($slayers_result, 'fetch_assoc')) {
                            while ($row = $slayers_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>#" . $rank . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . $row['Level'] . "</td>";
                                echo "<td>" . number_format($row['Fame']) . "</td>";
                                echo "</tr>";
                                $rank++;
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data available</td></tr>";
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
                        if ($use_mock_data && isset($vampires_mock)) {
                            foreach ($vampires_mock as $row) {
                                echo "<tr>";
                                echo "<td>#" . $rank . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . $row['Level'] . "</td>";
                                echo "<td>" . number_format($row['Fame']) . "</td>";
                                echo "</tr>";
                                $rank++;
                            }
                        } elseif ($vampires_result && is_object($vampires_result) && method_exists($vampires_result, 'fetch_assoc')) {
                            while ($row = $vampires_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>#" . $rank . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . $row['Level'] . "</td>";
                                echo "<td>" . number_format($row['Fame']) . "</td>";
                                echo "</tr>";
                                $rank++;
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data available</td></tr>";
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
                        if ($use_mock_data && isset($ousters_mock)) {
                            foreach ($ousters_mock as $row) {
                                echo "<tr>";
                                echo "<td>#" . $rank . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . $row['Level'] . "</td>";
                                echo "<td>" . number_format($row['Fame']) . "</td>";
                                echo "</tr>";
                                $rank++;
                            }
                        } elseif ($ousters_result && is_object($ousters_result) && method_exists($ousters_result, 'fetch_assoc')) {
                            while ($row = $ousters_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>#" . $rank . "</td>";
                                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                echo "<td>" . $row['Level'] . "</td>";
                                echo "<td>" . number_format($row['Fame']) . "</td>";
                                echo "</tr>";
                                $rank++;
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data available</td></tr>";
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

