<?php
/* Log out process, unsets and destroys session variables */
session_start();
session_unset();
session_destroy(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Log Out</title>
  <?php include 'css/css.html'; ?>
</head>

<body>

    <div class="container fluid">
      <div class="alert alert-primary text-center" role="alert">
          <h1>Thanks for stopping by</h1>
          <p><?= 'You have been logged out!'; ?></p>
      </div>
          
      <a href="index.php"><button class="btn btn-lg btn-primary btn-block"/>Home</button></a>
    </div>

    <?php include 'js/js.html'; ?>
</body>
</html>
