<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require './assets/vendor/autoload.php';

// Include necessary files
include('./config/db_connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email entered by user
    $email = $_POST['email'];

    // Query the database to check if the email exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    // If the email exists
    if (mysqli_num_rows($result) == 1) {
        // Generate a random verification code
        $verification_code = mt_rand(100000, 999999);

        // Update the user's verification code in the database
        $update_sql = "UPDATE users SET verification_code = '$verification_code' WHERE email = '$email'";
        mysqli_query($conn, $update_sql);

        // Send an email to the user with the verification code
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com '; // Your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'johnpatrick.lubuguin@gmail.com'; // SMTP username
            $mail->Password   = 'fccn sojk imne atvr'; // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587; // Port for TLS encryption

            // Recipients
            $mail->setFrom('johnpatrick.lubuguin@gmail.com', 'patrick');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body    = 'Your verification code for password reset is: ' . $verification_code;

            $mail->send();
            echo "One time Verification code sent to your email. <a href='reset_password.php'>Reset Password</a>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // If the email does not exist in the database
        echo "Email not found. Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/login.css" />
    <title>Login Page</title>
</head>
<body>
<div class="container">
    
    <div class="forms-container">
    
        <div class="signin-signup">
        
            <form method="post" action="">
                <img src="img/1.png" class="image" alt="" style="width: 60%;"/>
                        <br>
                        <br>
                    <h3>Forgot Password</h3>
                    <div class="input-field">
                        <i class="fas fa-user" ></i>
                        <input type="email" id="email" name="email"  placeholder="Enter Email Address" required>
                    
                    </div>
                    <a role="button" class="btn solid btn2" href="login.php" >cancel</a>
                    <button class="btn solid" type="submit" value="Reset Password" style="margin-left: 130px;">submit</button>
                    
            </form>
        </div>
    </div>

    <div class="panels-container">
        <div class="panel left-panel">
            <div class="content">
                <h3> Jollibee User Management and Payroll System</h3>
            </div>
            <img src="img/register.svg" class="image" alt="" style="width: 100%; padding-left: 20%;"/>
        </div>
    </div>
</div>    


   
</body>
</html>
