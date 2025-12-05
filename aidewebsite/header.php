<?php
if (!isset($page_title)) {
    $page_title = "DarkEden Server Panel";
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
    <div class="container">
        <header>
            <h1>🌙 DarkEden Server Panel</h1>
            <p>Server Administration & Statistics</p>
        </header>
        
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="rankings.php">Rankings</a></li>
                <li><a href="players.php">Players</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
        
        <div class="content">

