<?php
session_start(); // Start the session
session_destroy(); // Destroy all session data

// You can optionally include additional cleanup or logging here

// Response message to indicate successful logout
$response = "You have been logged out successfully.";
echo $response;
?>