<?php

    // POST Variables
    //      $_POST["user_id"]       - user's email
   
    // Return
    //      JSON
    //          "poi_id"            - poi id number
    //          "poi_name"          - poi name
    //          "poi_latitude"      - poi location latitude
    //          "poi_longituge"     - poi location longitude
    //          "description"       - poi description

    // connect to db
    $host = 'us-cdbr-iron-east-05.cleardb.net';
    $user = 'b90eb97b555c5e';
    $pass = 'bdfd5fcd';
    $db = 'heroku_ddc26ba47105536';
    $mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

    // user email
    
    $user_email = $_POST['user_id'];



    // get user's id from the email
    $sql = "SELECT * FROM users WHERE email = $user_email";
    $result = $mysqli->query($sql);
    $user_row = $result->fetch_assoc();
    $user_id = $user_row['id']
    echo $user_id;

    // query from db
    $sql = "SELECT user_id, id, name, latitude, longitude, description 
            FROM (points_of_interest poi
                INNER JOIN ownership o
                    ON id = o.poi_id)
            WHERE $user_id = o.user_id";

    // query result into array
    $user_poi_list = array();
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            $user_poi_list[] = array(
            'poi_id' => $row["id"],
            'name' => $row["name"],
            'latitude' => floatval($row["latitude"]),
            'longitude' => floatval($row["longitude"]),
            'description' => $row["description"]);
        }
    }
    // array into json
    $user_poi_json = json_encode($user_poi_list);
    echo $user_poi_json;

    // close db connection
    $mysqli->close();

?>
