<?php
/* Delete poi from database */
require_once('db.php');
session_start();

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");    
}
else {
    // Makes it easier to read
    $userid = $_SESSION['id'];
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
  <title>Delete existing POI<?= $username ?></title>
  <?php include 'css/css.html'; ?>
</head>


<body>
    <div class="container fluid">

        <?php
            $poi_id=$_GET['id'];
            $sql1 = "DELETE from points_of_interest WHERE id = " . $poi_id;
            if($mysqli->query($sql1)) {
        ?>
                <div class="alert alert-success text-center" role="alert">
                    <h5>The POI was deleted.</h5>
                </div>
        <?php
            } else {
        ?>
                <div class="alert alert-warning text-center" role="alert">
                    <h5>Failed to delete POI.</h5>
                </div>
        <?php
            }
        ?>

        </div>

        <a href="main.php"><button class="btn btn-lg btn-primary btn-block mt-2">Back to My Profile</button></a>
    
    <?php include 'js/js.html'; ?>
    <?php $mysqli->close(); ?>
</body>
</html>