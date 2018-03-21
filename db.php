<?php

/* Database connection settings */
$host = 'us-cdbr-iron-east-05.cleardb.net';
$user = 'b90eb97b555c5e';
$pass = 'bdfd5fcd';
$db = 'heroku_ddc26ba47105536';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

?>