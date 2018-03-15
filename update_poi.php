<?php
/* Update poi into the database */
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
  <title>Update existing POI<?= $username ?></title>
  <?php include 'css/css.html'; ?>
</head>


<body>
    <div class="container fluid">

        <?php
            $poi_id=$_GET['id'];
            $poi_name=$_POST['name'];
            $poi_x=$_POST['latitude'];
            $poi_y=$_POST['longitude'];
            $description=$_POST['description'];
            $sql1 = "UPDATE points_of_interest SET 
              latitude='" . $poi_x . "' ,
              longitude='" . $poi_y . "' ,
              name='" . $poi_name . "' ,
              description='" . $description  . "' 
              WHERE id = '" . $poi_id  . "'";
            if($mysqli->query($sql1)) {
        ?>
                <div class="alert alert-success text-center" role="alert">
                    <h5>The POI was successfully updated.</h5>
                </div>
        <?php            
            } else {
        ?>
                <div class="alert alert-warning text-center" role="alert">
                    <h5>Failed to update POI.</h5>
                </div>
        <?php
            }
        ?>

        <a href="profile.php"><button class="btn btn-lg btn-primary btn-block mt-2">Back to My Profile</button></a>

    </div>
    
    <?php include 'js/js.html'; ?>
    <?php $mysqli->close(); ?>
</body>
</html>