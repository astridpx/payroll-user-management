<?php
// Include necessary files
include('./config/db_connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];
    $new_password = $_POST['new_password'];

    // Verify the verification code against the database
    $sql = "SELECT * FROM users WHERE email = '$email' AND verification_code = '$verification_code'";
    $result = mysqli_query($conn, $sql);

    // If verification code is valid
    if (mysqli_num_rows($result) == 1) {
        // Update the user's password in the database
        
        $update_sql = "UPDATE users SET password = '$new_password', verification_code = NULL WHERE email = '$email'";
        mysqli_query($conn, $update_sql);

        echo "Password reset successful. You can now <a href='login.php'>login</a> with your new password.";
    } else {
        // If verification code is invalid
        echo "Invalid verification code. Please try again or request a new code.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="post" action="">
        <label for="email">Enter your email address:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="verification_code">Enter verification code:</label><br>
        <input type="text" id="verification_code" name="verification_code" required><br><br>
        <label for="new_password">Enter new password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <input type="submit" value="Reset Password">
   
