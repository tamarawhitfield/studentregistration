<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="container">
    <h2>You have been logged out</h2>
    <p>Thank you for using our service. You can log in again anytime.</p>
    <a href="login.php"><button>Login Again</button></a>
</div>

</body>
</html>
