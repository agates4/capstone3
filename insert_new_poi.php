<?php
/* Inserts poi into the database */
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
  <title>New POI into Database<?= $username ?></title>
  <?php include 'css/css.html'; ?>
</head>


<body>
    <div class="container fluid">

        <?php
            $poi_name=$_POST['name'];
            $poi_x=$_POST['latitude'];
            $poi_y=$_POST['longitude'];
            $description=$_POST['description'];
            $sql1 = "INSERT INTO points_of_interest (latitude, longitude, name, description) 
                        VALUES (" . $poi_x . ", " . $poi_y . ", '" . $poi_name . "', '" . $description . "')";
            if($mysqli->query($sql1)) {
        ?>
                <div class="alert alert-success text-center" role="alert">
                    <h5>New POI was successfully created.</h5>
                </div>
        <?php
                $poi_id = $mysqli->insert_id;
                $sql2 = "INSERT INTO ownership (poi_id, user_id) 
                            VALUES (" . $poi_id . ", " . $userid . ")";
                if($mysqli->query($sql2)) {
        ?>
                    <div class="alert alert-success text-center" role="alert">
                        <h5>New POI ownership was successfully created.</h5>
                    </div>
        <?php
                } else {
        ?>
                    <div class="alert alert-warning text-center" role="alert">
                        <h5>Failed to created POI ownership.</h5>
                    </div>
        <?php            
                } 
            } else {
        ?>
                <div class="alert alert-warning text-center" role="alert">
                    <h5>Failed to created POI.</h5>
                </div>
        <?php
            }
        ?>

        <a href="main.php"><button class="btn btn-lg btn-primary btn-block mt-2">Back to My Profile</button></a>

    </div>
    
    <?php include 'js/js.html'; ?>
    <?php $mysqli->close(); ?>
</body>
</html>
