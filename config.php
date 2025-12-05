<?php
/**
 * DarkEden Web Panel Configuration
 * Modernized version - Updated for PHP 8.1+
 * Based on original DK2WEB by PeChU and BloodyShade
 */

// Prevent direct access
if(stristr($_SERVER['PHP_SELF'], "config.php")) die('FATAL ERROR: Direct access denied');

// Database Configuration
$host = "localhost"; // Database host (use localhost if MySQL is on same server)
$user = "elcastle"; // Database user
$pass = "elca110"; // Database password
$db = "DARKEDEN"; // Database name

// Server Ports
$loginport = 9999; // Login server port
$gameport = 9998; // Game server port

// Shop Configuration
$medalvalue = 150; // Value of each Gold Medal in shop points
$jobprice = 200; // Price of Job Change (JC) on website

// Ranking Configuration
// Set the amount of players that will appear in the top for each race
$slayer_rank_limit = 5; // Number of Slayers in TOP RANK
$vampire_rank_limit = 5; // Number of Vampires in TOP RANK
$ousters_rank_limit = 5; // Number of Ousters in TOP RANK

// Database Connection (Modern MySQLi)
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to utf8
$mysqli->set_charset("utf8");

// Helper Functions
function isAlNum($str) {
    return preg_match("/^[a-zA-Z0-9]+$/", $str);
}

function isNum($str) {
    return preg_match("/^[0-9]+$/", $str);
}

function ismail($str) {
    return filter_var($str, FILTER_VALIDATE_EMAIL) !== false;
}

// Sanitize input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Get database connection (for use in other files)
function getDB() {
    global $mysqli;
    return $mysqli;
}

?>

