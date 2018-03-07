<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");    
}
else {
    // Makes it easier to read
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
}
?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Welcome <?= $username ?></title>
  <?php include 'css/css.html'; ?>
</head>


<body>
    <div class="conatiner fluid">

        <div class="alert alert-primary text-center" role="alert">
            <h1>Welcome</h1>
            <?php 
            // Display message about account verification link only once
            if ( isset($_SESSION['message']) ) {
            ?>
                <?php echo $_SESSION['message']; ?>
            <?php
                // Don't annoy the user with more messages upon page refresh
                unset( $_SESSION['message'] );
            }
            ?>
            <h2><?php echo $username; ?></h2>
            <p><?= $email ?></p>
        </div>
        
        <a href="logout.php"><button class="btn btn-lg btn-primary btn-block" name="logout"/>Log Out</button></a>

    </div>
    
    <?php include 'js/js.html'; ?>
</body>
</html>
