<?php
/**
 * DarkEden Web Panel - Login
 */
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $playerid = clean_input($_POST['playerid'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($playerid) || empty($password)) {
        $error = 'Please enter Player ID and Password';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT PlayerID, Password FROM Player WHERE PlayerID = ?");
        $stmt->bind_param("s", $playerid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $player = $result->fetch_assoc();
            // Note: Original used plaintext password comparison
            // You should implement proper password hashing
            if ($player['Password'] == $password) {
                session_start();
                $_SESSION['playerid'] = $playerid;
                $_SESSION['logged_in'] = true;
                header('Location: home.php');
                exit;
            } else {
                $error = 'Invalid Player ID or Password';
            }
        } else {
            $error = 'Invalid Player ID or Password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DarkEden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <h1>Player Login</h1>
    
    <div class="section" style="max-width: 400px; margin: 0 auto;">
        <?php if ($error): ?>
            <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Player ID:</label>
                <input type="text" name="playerid" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Password:</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <button type="submit" class="btn" style="width: 100%;">Login</button>
        </form>
        
        <p style="margin-top: 20px; text-align: center;">
            <a href="reg/reg.php">Register New Account</a>
        </p>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>

