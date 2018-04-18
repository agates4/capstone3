<?php

    // POST Variables
    //      $_POST["user_id"]       - user id number
    //      $_POST["latitude"]      - new poi location latitude
    //      $_POST["longitude"]     - new poi location longitude
    //      $_POST["name"]          - new poi name
    //      $_POST["description"]   - new poi description

    // Return
    //      message
    //          tells if new poi inserted successfully
    //          tells if new ownership inserted successfully

    // connect to db
    $host = 'us-cdbr-iron-east-05.cleardb.net';
    $user = 'b90eb97b555c5e';
    $pass = 'bdfd5fcd';
    $db = 'heroku_ddc26ba47105536';
    $mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

    // new poi inforamtion from front end
    $user_id = $_POST["user_id"];
    $poi_x = $_POST["latitude"];
    $poi_y = $_POST["longitude"];
    $poi_name = $_POST["name"];
    $description = $_POST["description"];
  
    // insert into database
    $sql_1 = "INSERT INTO points_of_interest (latitude, longitude, name, description) 
              VALUES (" . $poi_x . ", " . $poi_y . ", '" . $poi_name . "', '" . $description . "')";

    if($mysqli->query($sql_1)) {
        $message_1 = "New poi was created.";
        $poi_id = $mysqli->insert_id;
        $sql_2 = "INSERT INTO ownership (poi_id, user_id) 
                  VALUES (" . $poi_id . ", " . $user_id . ")";
        if($mysqli->query($sql_2)) {
            $message_2 = "New ownership was created.";
        }
        else {
            $message_2 = "Failed to create new ownership.";
        }
    }
    else {
        $message_1 = "Failed to create new poi.";
    }

    // message to front end
    echo $message_1 . ' ' . $message_2;

    // close db connection
    $mysqli->close();

    ?>