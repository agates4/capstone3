<?php
/* Displays all successful messages */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Success</title>
  <?php include 'css/css.html'; ?>
</head>

<body>
<div class="container-fluid">
    <div class="alert alert-primary text-center" role="alert">
        <h1><?= 'Success'; ?></h1>
        <p>
        <?php 
        if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ):
            echo $_SESSION['message'];    
        else:
            header( "location: index.php" );
        endif;
        ?>
        </p>
    </div>
    <a href="index.php"><button class="btn btn-lg btn-primary btn-block"/>Home</button></a>
</div>

<?php include 'js/js.html'; ?>
</body>
</html>
