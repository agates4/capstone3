<?php

// Include config file
require_once 'db.php';

//drop tables if exists
$mysqli->query('DROP TABLE IF EXISTS `test_ownership`;') or die($mysqli->error);
$mysqli->query('DROP TABLE IF EXISTS `test_user`;') or die($mysqli->error);
$mysqli->query('DROP TABLE IF EXISTS `test_poi`;') or die($mysqli->error);

//create users table with all the fields
$mysqli->query('
CREATE TABLE `test_user` 
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`ID`) 
);') or die($mysqli->error);

//create POI table with all the fields
$mysqli->query('
CREATE TABLE `test_poi` 
(
    `ID` INT NOT NULL AUTO_INCREMENT,
    `Latitude` FLOAT NOT NULL,
    `Longitude` FLOAT NOT NULL,
    `Name` VARCHAR(100) NOT NULL,
    `Description` TEXT,
    PRIMARY KEY (`ID`) 
);') or die($mysqli->error);

//create Ownership table with all the fields
$mysqli->query('
CREATE TABLE `test_ownership` 
(
    `POI_ID` INT NOT NULL,
    `Owner_ID` INT NOT NULL,
    FOREIGN KEY (POI_ID) REFERENCES test_poi (ID),
    FOREIGN KEY (Owner_ID) REFERENCES test_user (ID)
);') or die($mysqli->error);

// Include config file
require_once 'db.php';

// Insert test users
for ($i = 1; $i <= 10; $i++) {
  $u = 'test' . $i;
  $e = 'test' . $i . '@kent.edu';
  $p = $mysqli->escape_string(password_hash('test1', PASSWORD_BCRYPT));
  $mysqli->query('
  INSERT INTO test_user (username, email, password) VALUES ("' . $u . '", "' . $e . '", "' . $p . '");
  ') or die($mysqli->error);
}

// Insert test pois
for ($i = 1; $i <= 10; $i++) {
  $x = 1.0;
  $y = 1.0;
  $n = 'poi' . $i;
  $d = 'description' . $i;
  $mysqli->query('
  INSERT INTO test_poi (Latitude, Longitude, Name, Description) VALUES ("' . $x . '", "' . $y . '", "' . $n . '", "' . $d . '");
  ') or die($mysqli->error);
}

// Insert test ownerships
for ($i = 1; $i <= 10; $i++) {
  $mysqli->query('
  INSERT INTO test_ownership (POI_ID, Owner_ID) VALUES ("' . $i . '", "' . $i . '");
  ') or die($mysqli->error);
}

?>