<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <title>Database - Group25</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <?php
      session_start();
      if(isset($_SESSION['user'])):
        include('header.php');?>
        <div class="justify-content-center">
          <div class="text-center">
            <h1>Bienvenue sur la base de données</h1>
            <p>Sur ce site, vous allez pouvoir parcourir la base de données de notre entreprise, et ce afin de réaliser différentes opérations.</p>
          </div>
          <div id="wrapper">
            <div id="header">
              Liste des onglets
            </div>
            <div id="content">
              <ul>
                <li>Tri : permet d'afficher une table désirée de la base de données et d'effectuer un tri sur les différentes colonnes de celle-ci.</li>
                <li>Tâches/Projet : permet d'afficher les tâche de tous les projets et des les filtrer selon un projet spécifique. Affiche également le coût des tâches grâce au taux horaire de la fonction de l'employé réalisant la tâche.</li>
                <li>Ajout : permet d'ajouter des projets, des employés et des fonctions via des formulaires. Permet également de modifier le département et la fonction d'un employé.</li>
                <li>Tâches : permet d'accéder à la création des tâches et l'imputation des heures de travail sur celles-ci et calculer le budget grâce au taux horaire des employés. Vous pourrez également décider de mettre un terme à un projet.</li>
                <li>Employé/projet : Permet d'afficher la liste des employés ayant participé à tous les projet, qu'ils soient employés, chefs ou expert et affiche leur responsabilité dans les différents projets.</li>
                <li>Projets : Tableau de bord concernant les projets et regroupant toutes les informations liées à ceux-ci.</li>
                <li>Employés : Tableau de bord concernant les employés et regroupant toutes les informations liées à ceux-ci. </li>
              </ul>
            </div>
          </div>
        </div>
      <?php
        include('footer.php');
      else:
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>