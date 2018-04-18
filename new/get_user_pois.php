<?php

    // POST Variables
    //      $_POST["user_id"]       - user id number
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

    // user id
    $user_id = $POST['user_id'];

    // user location
    $user_lat = $_POST["latitude"];
    $user_lng = $_POST["longitude"];

    // query from db
    $sql = "SELECT user_id, id,
            ( 3959 * acos( cos( radians( '$user_lat' ) ) * cos( radians( latitude ) ) 
            * cos( radians( longitude ) - radians( '$user_lng' ) ) 
            + sin( radians( '$user_lat' ) ) * sin(radians(latitude)) ) )
            AS distance, 
            name, 
            latitude, 
            longitude 
    FROM (points_of_interest poi
      INNER JOIN ownership o
        ON poi.id = o.poi_id) 
    HAVING distance < 15 
    ORDER BY distance 
    LIMIT 0 , 30";

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