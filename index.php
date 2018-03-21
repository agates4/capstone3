<?php 
/* Main page with two forms: sign up and log in */
require_once('db.php');
session_start();
?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login Form</title>
	<?php include 'css/css.html'; ?>
</head>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['login'])) { //user logging in
        require 'login.php';
    }
    elseif (isset($_POST['register'])) { //user registering
        require 'register.php';
    }
}
?>

<body>

  <div class="container fluid">
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link" href="signup.php">Sign up</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="index.php">Log In</a>
      </li>
    </ul>

    <div class="mt-5" id="login">   

      <div class="alert alert-primary text-center" role="alert">
        <h2>Welcome to<br>Street View Assistance</h2>
      </div>

      <form action="index.php" method="post" autocomplete="off">
        <div class="form-group">
          <label for="InputEmail">Email address</label>
          <input type="email" class="form-control" id="InputEmail" placeholder="Enter email" name="email" required>
        </div>
        <div class="form-group">
          <label for="InputPassword">Password</label>
          <input type="password" class="form-control" id="InputPassword" placeholder="Enter Password" name="password" required>
        </div>
        <button type="submit" class="btn btn-lg btn-primary btn-block" name="login">Log In</button>
      </form>

    </div>

  </div>
  <?php include 'js/js.html'; ?>
  <?php $mysqli->close(); ?>
</body>

</html>
