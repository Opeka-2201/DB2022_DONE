<?php
try
{
  $host = 'ms8db';
  $db = new PDO('mysql:' . $host  . ';dbname=group25;charset=utf8', $_SESSION['user'], $_SESSION['pwd']);
}
catch (Exception $e)
{
  echo $e->getMessage();
  die('Erreur! ' . $host);
}
?>