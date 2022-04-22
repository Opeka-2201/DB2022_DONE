<!DOCTYPE html>

<html>
  <head>
  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }
  </style>
    <meta charset="utf-8" />
    <title>Database - Tâches/Projets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <?php
      require __DIR__ . '/functions.php';
      include("header.php");
      try
      {
        $db = new PDO('mysql:host=localhost;dbname=group25;charset=utf8', 'root', 'root');
      }
      catch (Exception $e)
      {
        die('Erreur!');
      } 
    ?>
    <div class="px-4 py-5 my-5">
      <div class="d-flex justify-content-center">
        <p>Choisissez un projet : </p>
        <form action ='tasks_project.php' method = 'GET'>
          <select name="projet" id="projet-select">
              <option value="NULL">---</option>
              <?php
                  $projects = sqlQuery('SELECT * FROM Projet',$db);
                  foreach ($projects as $projet): 
              ?>
                  <option name ="projet" value="<?php echo($projet["NOM"])?>"><?php echo($projet["NOM"])?> </option>
              <?php endforeach;?>
          </select>
        <button type="submit">Accéder</button>
        </form>
      </div>



      <div class="justify-content-center">
        <?php 
          if(isset($_GET["projet"]) && ($_GET["projet"] != "NULL")):
          ?>
            <p>Tâches relatives au projet <?php echo($_GET["projet"]);?> :</p>
          <?php
            $tasks = sqlQuery("SELECT Tache.ID, Tache.PROJET, Tache.EMPLOYE, Employe.NOM, Tache.NB_HEURES, Employe.NOM_FONCTION, Fonction.TAUX_HORAIRE, Fonction.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN Fonction ON Employe.NOM_FONCTION = Fonction.NOM WHERE Tache.PROJET = '" . $_GET["projet"] . "'", $db);
            $columns = array(array("ID"),array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
            printTable($tasks, $columns);

          else:
            $tasks = sqlQuery('SELECT Tache.ID, Tache.PROJET, Tache.EMPLOYE, Employe.NOM, Tache.NB_HEURES, Employe.NOM_FONCTION, Fonction.TAUX_HORAIRE, Fonction.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN Fonction ON Employe.NOM_FONCTION = Fonction.NOM', $db);
            $columns = array(array("ID"),array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
            printTable($tasks, $columns);
          endif;
        ?>
      </div>
    </div>
  </body>
</html>