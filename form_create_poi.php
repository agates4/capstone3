<?php
/* Input form to create new poi */
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
  <title>Create New POI <?= $username ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
    <div class="container fluid">

        <div class="alert alert-primary text-center" role="alert">
            <h1>Create New POI</h1>
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

        <form method="POST" action="insert_new_poi.php">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" required>
          </div>
        <div class="form-group">
            <label>Latitude</label>
            <input type="number" step="0.0000001" class="form-control" name="latitude" required>
          </div>
          <div class="form-group">
            <label>Longitude</label>
            <input type="number" step="0.0000001" class="form-control" name="longitude" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" name="description">
          </div>
          <button type="submit" class="btn btn-lg btn-primary btn-block">Submit</button>
        </form>
        <a href="main.php" role="button" class="btn btn-lg btn-primary btn-block mt-2">Cancel</a>
    </div>
    
    <?php include 'js/js.html'; ?>
</body>
</html>
