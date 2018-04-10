<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
         
            $user_lat = $_POST["latitude"];
            $user_lng = $_POST["longitude"];
         
            $sql = "SELECT id, ( 3959 * acos( cos( radians( '$user_lat' ) ) * cos( radians( latitude ) ) 
            * cos( radians( longitude ) - radians( '$user_lng' ) ) + sin( radians( '$user_lat' ) ) * sin(radians(latitude)) ) ) AS distance, name, latitude, longitude 
            FROM points_of_interest 
            HAVING distance < 15 
            ORDER BY distance 
            LIMIT 0 , 30";
            
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0){
                while ($row = $result->fetch_assoc()){
                    echo "id: " . $row["id"]. " - name: " . $row["name"] ." ". $row["latitude"]. " " . $row["longitude"]. "<br>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
        ?>
    </body>
</html>
