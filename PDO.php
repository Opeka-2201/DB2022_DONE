<?php
try
{
  $host = 'localhost';
<<<<<<< HEAD
  $db = new PDO('mysql:' . $host  . ';dbname=group25;charset=utf8', $_SESSION['user'], $_SESSION['pwd']);
=======
  $db = new PDO('mysql:host=' . $host  . ';dbname=group25;charset=utf8', $_SESSION['user'], $_SESSION['pwd']);
>>>>>>> 5e2a1ad (inTransaction test)
}
catch (Exception $e)
{
  echo $e->getMessage();
  die('Erreur! ');
}
?>