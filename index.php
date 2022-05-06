<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<!DOCTYPE html>

<?php session_start(); // Début session pour vérifier connexion ?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Database</title>
  </head>

  <body>
    <?php
      if(isset($_SESSION["user"])):
        // si connecté entre sur le site
        header("Location:home.php");
      else:
        // si non connecté est renvoyé vers login.php
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>