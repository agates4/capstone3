<?php
/* Input form to edit existing poi */
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
  <title>Edit POI <?= $username ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
    <div class="container fluid">

        <div class="alert alert-primary text-center" role="alert">
            <h1>Edit POI</h1>
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
        $poi_x = $row["latitude"];
        $poi_y = $row["longitude"];
        $description = $row["description"];
        ?>

        <form method="POST" action="update_poi.php?id=<?php echo $poi_id; ?>">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
          </div>
        <div class="form-group">
            <label>Latitude</label>
            <input type="number" step="0.0000001" class="form-control" name="latitude" value="<?php echo $poi_x; ?>">
          </div>
          <div class="form-group">
            <label>Longitude</label>
            <input type="number" step="0.0000001" class="form-control" name="longitude" value="<?php echo $poi_y; ?>">
          </div>
          <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
          </div>
          <button type="submit" class="btn btn-lg btn-warning btn-block">Submit</button>
        </form>
        <a href="main.php" role="button" class="btn btn-lg btn-primary btn-block mt-2">Cancel</a>
    </div>
    
    <?php include 'js/js.html'; ?>

</body>
</html>
<?php $mysqli->close(); ?>
