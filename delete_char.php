<?php
/**
 * DarkEden Web Panel - Delete Character
 */
require_once 'config.php';

session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$playerid = $_SESSION['playerid'];
$charname = clean_input($_GET['name'] ?? '');

if (empty($charname)) {
    header('Location: home.php');
    exit;
}

$db = getDB();

// Verify character belongs to player
$stmt = $db->prepare("SELECT Name FROM PlayerCreature WHERE Name = ? AND PlayerID = ?");
$stmt->bind_param("ss", $charname, $playerid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('Character not found or does not belong to you');
}

// Delete character
$stmt = $db->prepare("DELETE FROM PlayerCreature WHERE Name = ? AND PlayerID = ?");
$stmt->bind_param("ss", $charname, $playerid);

if ($stmt->execute()) {
    header('Location: home.php?deleted=1');
} else {
    die('Error deleting character: ' . $db->error);
}
?>

