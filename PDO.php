<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<?php
  try
  {
    $host = 'localhost';
    $db = new PDO('mysql:host=' . $host  . ';dbname=group25;charset=utf8', $_SESSION['user'], $_SESSION['pwd']);
  }
  catch (Exception $e)
  {
    echo $e->getMessage();
    die('Erreur! ');
  }
?>