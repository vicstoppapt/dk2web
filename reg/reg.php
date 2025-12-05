<?php
/**
 * DarkEden Web Panel - Player Registration
 */
require_once '../config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $playerid = clean_input($_POST['playerid'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $ssn = clean_input($_POST['ssn'] ?? '');
    
    // Validation
    if (empty($playerid) || strlen($playerid) < 4) {
        $error = 'Player ID must be at least 4 characters';
    } elseif (!isAlNum($playerid)) {
        $error = 'Player ID can only contain letters and numbers';
    } elseif (empty($password) || strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password != $password2) {
        $error = 'Passwords do not match';
    } elseif (empty($ssn)) {
        $error = 'SSN is required';
    } else {
        $db = getDB();
        
        // Check if player ID already exists
        $stmt = $db->prepare("SELECT PlayerID FROM Player WHERE PlayerID = ?");
        $stmt->bind_param("s", $playerid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Player ID already exists';
        } else {
            // Create new player account
            // Note: Original used plaintext password - you should hash it
            $stmt = $db->prepare("INSERT INTO Player (PlayerID, Password, SSN, LogOn, Access) VALUES (?, ?, ?, NOW(), 'N')");
            $stmt->bind_param("sss", $playerid, $password, $ssn);
            
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now login.';
            } else {
                $error = 'Registration failed: ' . $db->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DarkEden</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    
    <h1>Create New Account</h1>
    
    <div class="section" style="max-width: 500px; margin: 0 auto;">
        <?php if ($error): ?>
            <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div style="background: #efe; color: #3c3; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($success); ?>
                <p><a href="../login.php">Go to Login</a></p>
            </div>
        <?php else: ?>
            <form method="POST" action="reg.php">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Player ID (4+ characters, letters/numbers only):</label>
                    <input type="text" name="playerid" required pattern="[a-zA-Z0-9]{4,}" 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Password (6+ characters):</label>
                    <input type="password" name="password" required minlength="6"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Confirm Password:</label>
                    <input type="password" name="password2" required minlength="6"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">SSN (Social Security Number):</label>
                    <input type="text" name="ssn" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Register</button>
            </form>
            
            <p style="margin-top: 20px; text-align: center;">
                <a href="../login.php">Already have an account? Login</a>
            </p>
        <?php endif; ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>

