<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
   // header("Location: /login.php");
   echo "fallse";
       exit();
}

echo "<h1>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
echo "<p>Your role is: " . htmlspecialchars($_SESSION['role']) . ".</p>";
echo "<a href='/logout.php'>Logout</a>";
?>
