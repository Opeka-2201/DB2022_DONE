<!DOCTYPE html>

<?php session_start(); ?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Database</title>
  </head>

  <body>
    <?php
      if(isset($_SESSION["user"])):
        header("Location:home.php");
      else:
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>