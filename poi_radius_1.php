<?php
// Curtis, I left all of your original code as comment
// Please check my modification  

// Seunghoon added it temporarily just for error checking
//    error_reporting(E_ALL);
//    ini_set('diaplay_errors', '1');
          // Seunghoon's modification - starts here
            $host = 'us-cdbr-iron-east-05.cleardb.net';
            $user = 'b90eb97b555c5e';
            $pass = 'bdfd5fcd';
            $db = 'heroku_ddc26ba47105536';
            $mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
          // Seunghoon's modification - ends here

            $user_lat = $_POST["latitude"]; // 41.14669;
            $user_lng = $_POST["longitude"]; // -81.342428;
         
            $sql = "SELECT id, ( 3959 * acos( cos( radians( '$user_lat' ) ) * cos( radians( latitude ) ) 
            * cos( radians( longitude ) - radians( '$user_lng' ) ) + sin( radians( '$user_lat' ) ) * sin(radians(latitude)) ) ) AS distance, name, latitude, longitude 
            FROM points_of_interest 
            HAVING distance < 15 
            ORDER BY distance 
            LIMIT 0 , 30";
            
          /* Curtis' original
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0){
                while ($row = $result->fetch_assoc()){
                    echo "id: " . $row["id"]. " - name: " . $row["name"] ." ". $row["latitude"]. " " . $row["longitude"]. "<br>";
                }
            } else {
                echo "0 results";
            }
          */

          // Seunghoon's modification - starts here
            $poi_list = array();
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0){
                while ($row = $result->fetch_assoc()){
                    $poi_list[] = array(
                    'id' => $row["id"],
                    'name' => $row["name"],
                    'latitude' => floatval($row["latitude"]),
                    'longitude' => floatval($row["longitude"]));
                }
            }
            $poi_json = json_encode($poi_list);
            echo $poi_json;
            // Seunghoon's modification - ends here

            /*
            $conn->close();
            */

            // Seunghoon's modification - starts here
            $mysqli->close();
            // Seunghoon's modification - ends here
?>
