<?php

    // POST Variables
    //      $_POST["email"]         - user's email
    //      $_POST["latitude"]      - user location latitude
    //      $_POST["longitude"]     - user location longitude

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
    $user_email = $POST['user_email'];
    
    // get user's id from the email
    $sql = "SELECT * FROM users WHERE email = $user_email";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // user location
    $user_lat = $_POST["latitude"];
    $user_lng = $_POST["longitude"];

    // query from db
    $sql = "SELECT user_id, id, name, latitude, longitude 
            FROM (points_of_interest poi
                INNER JOIN ownership o
                    ON poi.id = o.poi_id)
            WHERE ownership.id = $user_id
            ORDER BY name";

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
