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
        $user_x = 41.14669;
        $user_y = -81.342428;
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


<body onload="sortTable(3)">
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
            $limit = 10;
        }
    ?>

        <div class="row">
            <div class="col-6">
                <a href="form_create_poi.php"><button class="btn btn-lg btn-justified btn-warning btn-block">
                    Create New POI</button></a>
            </div>
            <div class="col-6">
                <a href="logout.php"><button class="btn btn-lg btn-justified btn-info btn-block" name="logout">
                    Log Out</button></a>
            </div>
        </div>

        <nav class="mt-2 navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="nav-link dropdown-toggle btn-primary" href="#" id="navbarDropdown" 
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Distance
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="main.php?limit=0.1">100 m</a>
            <a class="dropdown-item" href="main.php?limit=0.2">200 m</a>
            <a class="dropdown-item" href="main.php?limit=0.3">300 m</a>
            <a class="dropdown-item" href="main.php?limit=0.5">500 m</a>
            <a class="dropdown-item" href="main.php?limit=1.0">1 km</a>
            <a class="dropdown-item" href="main.php?limit=10.0">10 km</a>
            </div>
            <input type="text" id="myInput" onkeyup="searchTable()" placeholder="Search POI Name" 
                title="Type in a name">
 
        </nav>
    <?php
        // query for user POI IDs
        $sql="SELECT poi_id FROM ownership 
                WHERE user_id = '" . $userid . "'";
        $result = $mysqli->query($sql);
        $user_poi_ids = array();
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                $user_poi_ids[] = $row["poi_id"];
            }
        }
    ?>
    <table class='table' id="myTable">
        <tr>
            <th id='poi_type'></th>
            <th class='text-center'>
                <button class="btn-md btn-info" onclick="sortTable(0)">Type</button>
            </th>
            <th>
                <button class="btn-md btn-info" onclick="sortTable(2)">Name</button>
            </th>
            <th class="text-center">
                <button class="btn-md btn-info" onclick="sortTable(3)">D(km)</button>
            </th>
            <th class='text-center'>Action</th>
        </tr>
    <?php
        // qery for POIs
        $sql="SELECT * FROM points_of_interest";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()){
            $poi_id   = $row["id"];
            if(in_array($poi_id, $user_poi_ids)) {
                $poi_type = 0;
                $poi_icon = "<a href=# role='button' class='btn-sm btn-warning'>
                                <img src='open-iconic/svg/person.svg'></a>";
                $action = "<a href='form_edit_poi.php?id=" . $poi_id .
                          " role='button' class='btn btn-sm btn-warning'>
                            <img src='open-iconic/svg/pencil.svg' alt='edit'></a>
                            <a href='form_delete_poi.php?id=" . $poi_id . 
                          " role='button' class='btn btn-sm btn-danger'>
                            <img src='open-iconic/svg/delete.svg' alt='edit'></a>";
            } else {
                $poi_type = 1;
                $poi_icon = "<a href=# role='button' class='btn-sm btn-info'>
                                <img src='open-iconic/svg/globe.svg'></a>";
                $action = '';
            }
            $name     = $row["name"];
            $desc     = $row["description"];
            $poi_x    = $row["latitude"];
            $poi_y    = $row["longitude"];
            $distance = calcCrow($user_x, $user_y, $poi_x, $poi_y);
            
            if($distance < $limit) {
                $distance = number_format($distance, 3);
                echo
                "<tr>
                    <td id='poi_type'>" . $poi_type . "</td>
                    <td class='text-center'>" . $poi_icon . "</td>
                    <td>" . $name . "</td>
                    <td class='text-center'>" . $distance . "</td>
                    <td class='text-center'>" . $action . "</td>
                </tr>";
            }
        }
    ?>
    </table>

    </div>
    
    <?php include 'js/js.html'; ?>
    <?php $mysqli->close(); ?>
    
</body>
</html>
