<?php

    // POST variables
    //      $_POST["username"]      - user name
    //      $_POST["email"]         - user email
    //      $_POST["password"]      - user password

    // Return
    //          "message"           - success or error message

    // connect to db
    $host = 'us-cdbr-iron-east-05.cleardb.net';
    $user = 'b90eb97b555c5e';
    $pass = 'bdfd5fcd';
    $db = 'heroku_ddc26ba47105536';
    $mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

    // Escape all $_POST variables to protect against SQL injections
    $username = $mysqli->escape_string($_POST['username']);
    $email = $mysqli->escape_string($_POST['email']);
    $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));

    // Check if user with that email already exists
    $result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error());

    // We know user email exists if the rows returned are more than 0
    if ($result->num_rows > 0) {
        $mysqli->close();
        die(header("HTTP/1.0 404 Not Found"));
    }
    else { // Email doesn't already exist in a database, proceed...

        // You will need 'hash' field if you want to add verification feature
        $sql = "INSERT INTO users (username, email, password) " 
                . "VALUES ('$username','$email','$password')";

        // Add user to the database
        if ($mysqli->query($sql)){
            $message = "Thank you for signing up! Your account was created.";
        }
        else {
            $mysqli->close();
            die(header("HTTP/1.0 404 Not Found"));
        }

    }

    $result = array('message' => $message);
    $result_json = json_encode($result);
    echo $result_json;
    
    // close db connection
    $mysqli->close();

?>
