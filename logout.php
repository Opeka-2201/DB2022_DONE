<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<?php
  session_start();
  unset($_SESSION['user']);
  unset($_SESSION['pwd']);
  header("Location:login.php");
?>