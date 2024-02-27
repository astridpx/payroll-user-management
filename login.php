<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="./assets/css/login.css" />
    <title>Login Page</title>
  </head>

	<?php include('./includes/login.php'); ?>
  <?php include('./config/db_connect.php'); ?>
	<?php
	session_start();
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=home");

	?>

  
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form class="sign-in-form" id="login-form">
            <h2 class="title">Sign in</h2>
          
            <br>

            
            <div class="input-field">
              <i class="fas fa-user" style="margin: 20px 0 0 20px;"></i>
              <input type="text" id="username" name="username" placeholder="Enter Username ID"/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock" style="margin: 20px 0 0 20px;"></i>
            	<input type="password" id="password" name="password" placeholder="Enter Password" />
            </div><button class="btn solid" >Login</button>
         
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3> Jollibee User Management and Payroll System</h3>
          </div>
          <img src="img/logo.svg" class="image" alt="" style="width: 50%; margin-right: 120px;"/>
        </div>
      
  </body>

  <script src="./assets/js/logo.js"></script>
  <script>
    $('#login-form').submit(function(e) {
      e.preventDefault()
      $('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
      if ($(this).find('.alert-danger').length > 0)
        $(this).find('.alert-danger').remove();
      $.ajax({
        url: './services/ajax.php?action=login',
        method: 'POST',
        data: $(this).serialize(),
        error: err => {
          console.log(err)
          $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
  
        },
        success: function(resp) {
          if (resp == 1) {
            location.href = 'index.php?page=home';
          } else if (resp == 2) {
            location.href = 'voting.php';
          } else {
            $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
            $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
          }
        }
      })
    })
  </script>
</html>
