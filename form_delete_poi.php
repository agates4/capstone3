<?php
/* Warning and confirmation before deleting poi */
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
  <title>Delete POI <?= $username ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
    <div class="container fluid">

        <div class="alert alert-danger text-center" role="alert">
            <h1>Delete POI</h1>
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
        </div>

        <?php
        // retrieve original data
        $poi_id = $_GET["id"];
        $sql = "SELECT * FROM points_of_interest WHERE id = '" . $poi_id . "'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $description = $row["description"];
        ?>

    <div class="alert alert-danger" role="alert">
        <h6>Are you sure you want to delete this poi?</h6>
        <h5><?php echo $name; ?></h5>
        <p><?php echo $description; ?></p>
    </div>

    <a href="delete_poi.php?id='<?php echo $poi_id; ?>'" role="button" class="btn btn-lg btn-danger btn-block mt-2">Delete</a>    
    <a href="main.php" role="button" class="btn btn-lg btn-primary btn-block mt-2">Cancel</a>
    
<?php include 'js/js.html'; ?>
</body>
</html>
<?php $mysqli->close(); ?>
