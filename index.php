<?php
/**
 * DarkEden Web Panel - Main Page
 */
require_once 'config.php';

$page_title = "DarkEden Server Panel";
?>
<?php
$page_title = "DarkEden Server Panel";
include 'header.php';
?>
            <?php
            // Get server statistics
            $db = getDB();
            
            // Total players
            $total_players = $db->query("SELECT COUNT(*) as total FROM Player")->fetch_assoc()['total'];
            
            // Online players (you may need to adjust this query based on your database structure)
            $online_players = 0; // Placeholder - implement based on your server structure
            
            // Total characters
            $total_chars = $db->query("SELECT COUNT(*) as total FROM PlayerCreature")->fetch_assoc()['total'] ?? 0;
            
            // Guilds
            $total_guilds = $db->query("SELECT COUNT(*) as total FROM GuildInfo")->fetch_assoc()['total'] ?? 0;
            ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Players</h3>
                    <div class="number"><?php echo number_format($total_players); ?></div>
                </div>
                
                <div class="stat-card">
                    <h3>Online Players</h3>
                    <div class="number"><?php echo number_format($online_players); ?></div>
                </div>
                
                <div class="stat-card">
                    <h3>Total Characters</h3>
                    <div class="number"><?php echo number_format($total_chars); ?></div>
                </div>
                
                <div class="stat-card">
                    <h3>Guilds</h3>
                    <div class="number"><?php echo number_format($total_guilds); ?></div>
                </div>
            </div>
            
            <div class="section">
                <h2>Server Information</h2>
                <p><strong>Login Port:</strong> <?php echo $loginport; ?></p>
                <p><strong>Game Port:</strong> <?php echo $gameport; ?></p>
                <p><strong>Server Status:</strong> <span style="color: green;">● Online</span></p>
            </div>
            
            <div class="section">
                <h2>Quick Links</h2>
                <p>
                    <a href="rankings.php" class="btn">View Rankings</a>
                    <a href="players.php" class="btn">Player Management</a>
                    <a href="shop.php" class="btn">Item Shop</a>
                </p>
            </div>
        </div>
        
<?php include 'footer.php'; ?>

