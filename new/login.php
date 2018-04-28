<?php

    // POST variables
    //      $_POST["email"]         - user email
    //      $_POST["password"]      - user password

    // Return
    //      JSON
    //          "message"           - success or error message
    //          "user_id"           - user id number

    // connect to db
    $host = 'us-cdbr-iron-east-05.cleardb.net';
    $user = 'b90eb97b555c5e';
    $pass = 'bdfd5fcd';
    $db = 'heroku_ddc26ba47105536';
    $mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

    // retrieve user information
    $email = $mysqli->escape_string($_POST['email']);
    $result = $mysqli->query("SELECT * FROM users WHERE email='$email'");

    // check registration
    if ( $result->num_rows == 0 ){ // user doesn't exist
        $message = "Failed to login - Not registered";
        $user_id = NULL;
    }
    else { // user exists
        $user = $result->fetch_assoc();
        $user_id = $user["id"];

        if ( password_verify($_POST['password'], $user['password']) ) { // password matches
            $message = "Successfully logged in";
        }
        else {  // password doesn't match
            $mysqli->close();
            die(header("HTTP/1.0 404 Not Found"));
        }
    }

    // send it to front end
    $login_result = array('message' => $message, 'user_id' => $user_id);
    $login_result_json = json_encode($login_result);
    echo $login_result_json;

    // close db connection
    $mysqli->close();

?>
