<?php

    // POST Variables
    //      $_POST["poi_id"]        - poi id number to be updated
    //      $_POST["latitude"]      - modified poi location latitude
    //      $_POST["longitude"]     - modified poi location longitude
    //      $_POST["name"]          - modified poi name
    //      $_POST["description"]   - modified poi description

    // Return
    //      message
    //          tells if poi updated successfully

    // connect to db
    $host = 'us-cdbr-iron-east-05.cleardb.net';
    $user = 'b90eb97b555c5e';
    $pass = 'bdfd5fcd';
    $db = 'heroku_ddc26ba47105536';
    $mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

    // user modification of existing poi
    $poi_id = $_POST["poi_id"];
    $poi_x = $_POST["latitude"];
    $poi_y = $_POST["longitude"];
    $poi_name = $_POST["name"];
    
    /*
    Removed:
        latitude='" . $poi_x . "' ,
        longitude='" . $poi_y . "' ,
        name='" . $poi_name . "' ,
    */

    
    if(isset($_POST["description"])) {
        $description = $_POST["description"];
        $sql = "UPDATE points_of_interest SET 
            description='" . $description  . "' 
            WHERE id = '" . $poi_id  . "'";
    } else {
        $sql = "UPDATE points_of_interest SET 
            latitude='" . $poi_x . "' ,
            longitude='" . $poi_y . "' ,
            name='" . $poi_name . "' 
            WHERE id = '" . $poi_id  . "'";
    }
    // update database
    if($mysqli->query($sql)) {
        $message = "The poi was updated.";
        $poi_id = $mysqli->insert_id;
    }
    else {
        $message = "Failed to create new poi.";
    }

    // message to front end
    echo $message;

    // close db connection
    $mysqli->close();

    ?>
