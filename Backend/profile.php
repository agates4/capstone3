<?php
/* Displays user information and some useful messages */
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
    if(isset($_GET['current_latitude']) and isset($_GET['current_longitude'])) {
        $user_x = $_GET['current_latitude'];
        $user_y = $_GET['current_longitude'];
    } else {
        $user_x = 41.0760;
        $user_y = -81.5100;
    }
}
?>
<?php
// Calculate distance between two points in km
function calcCrow($lat1, $lon1, $lat2, $lon2){
        $R = 6371; // km
        $dLat = toRad($lat2-$lat1);
        $dLon = toRad($lon2-$lon1);
        $lat1 = toRad($lat1);
        $lat2 = toRad($lat2);

        $a = sin($dLat/2) * sin($dLat/2) +sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2); 
        $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
        $d = $R * $c;
        return $d;
}

// Converts numeric degrees to radians
function toRad($Value) 
{
    return $Value * pi() / 180;
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
    <div class="container fluid">

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
    <?php
        // set the radius to find nearby POIs
        if(isset($_GET["limit"])){
            $limit = $_GET["limit"];
        } else {
            $limit = 50000;
        }
    ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="profile.php">Nearby User POI</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Distance
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="profile.php?limit=0.1">100m</a>
                <a class="dropdown-item" href="profile.php?limit=0.2">200m</a>
                <a class="dropdown-item" href="profile.php?limit=0.3">300m</a>
                <a class="dropdown-item" href="profile.php?limit=0.5">500m</a>
                <a class="dropdown-item" href="profile.php?limit=1.0">1km</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="profile.php">All</a>
                </div>
            </li>
        </div>
        </nav>
    <?php
        // query for user POIs
        $sql="SELECT poi_id FROM ownership 
                WHERE user_id = '" . $userid . "'";
        $result = $mysqli->query($sql);
        if($result->num_rows > 0) {
    ?>
    <?php
            echo "<table class='table'><tr><th>Name</th><th></th></tr>";
            while($row = $result->fetch_assoc()){
                $poi_id = $row["poi_id"];
                $sql1="SELECT * FROM points_of_interest WHERE id = " . $poi_id;
                $result1 = $mysqli->query($sql1);
                $row1 = $result1->fetch_assoc();
                $poi_x = $row1["latitude"];
                $poi_y = $row1["longitude"];
                $distance = calcCrow($user_x, $user_y, $poi_x, $poi_y);
                if($distance < $limit) {
                    $poi_name = $row1["name"];
                    echo "<tr><td>" . $poi_name . "</td>
                        <td class='text-right'><a href='form_edit_poi.php?id=" . 
                            $poi_id . " role='button' class='btn btn-sm btn-warning'>
                            <img src='../open-iconic/svg/pencil.svg' alt='edit'></a>
                        <a href='form_delete_poi.php?id=" . 
                            $poi_id . " role='button' class='btn btn-sm btn-danger'>
                            <img src='../open-iconic/svg/delete.svg' alt='edit'></a></td>
                    </tr>";
                }
            }
            echo "</table>";
        }
    ?>
        <a href="form_create_poi.php"><button class="btn btn-lg btn-justified btn-default btn-block">Create New POI</button></a>
        <a href="logout.php"><button class="btn btn-lg btn-primary btn-block mt-2" name="logout">Log Out</button></a>

    </div>
    
    <?php include 'js/js.html'; ?>
    <?php $mysqli->close(); ?>
</body>
</html>
