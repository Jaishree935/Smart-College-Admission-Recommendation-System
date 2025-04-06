<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?>!</h1>
    <p>Your Email: <?php echo htmlspecialchars($_SESSION["email"]); ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
