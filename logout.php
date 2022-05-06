<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<?php
  // Récupération de la session
  session_start();
  // Unset des variables de session
  unset($_SESSION['user']);
  unset($_SESSION['pwd']);
  // Redirection vers login.php
  header("Location:login.php");
?>