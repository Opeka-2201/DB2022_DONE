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
    <title>Database - Tasks/Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <?php
      try
      {
        $db = new PDO('mysql:host=localhost;dbname=group25;charset=utf8', 'root', 'root');
      }
      catch (Exception $e)
      {
        die('Erreur!');
      }
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

      <form action ='tasks_project.php' method = 'GET'>
          <input name="projet" value="NULL" type ="hidden">
          <label for="project_id">Project ID : </label>
          <input type="text" id="project_id" name="project_id">
          <br><br>
          <label for="project_hours">Project hours : </label>
          <input type="text" id="project_hours" name="project_hours">
          <br><br>

          <button type="submit">Soumettre heures</button>
          <br><br>
      </form>

      <div class="justify-content-center">
        <?php 
              if(isset($_GET["projet"]) && !empty($_GET["project_id"]) && !empty($_GET["project_hours"])):
                sqlQuery("UPDATE Tache SET NB_HEURES = " . $_GET["project_hours"] . " WHERE ID = " . $_GET["project_id"], $db);
                $tasks = sqlQuery('SELECT T.ID as "ID", T.EMPLOYE as "EMPLOYE" , E.NOM as "NOM", T.PROJET as "PROJET", E.NOM_FONCTION as "FONCTION", F.TAUX_HORAIRE as "TAUX_HORAIRE", T.NB_HEURES as "NB_HEURES", F.TAUX_HORAIRE * T.NB_HEURES as "COUT" FROM Tache T, Employe E, Fonction F WHERE T.EMPLOYE = E.NO AND E.NOM_FONCTION = F.NOM', $db);
                $columns = array(array('ID'),array("EMPLOYE"),array("NOM"),array("PROJET"),array("FONCTION"),array("TAUX_HORAIRE"),array("NB_HEURES"),array("COUT"));
                printTable($tasks, $columns);
              
                elseif((isset(($_GET["project_id"])) && isset($_GET["project_hours"])) && (empty($_GET["project_id"]) || empty($_GET["project_hours"]))):
                ?>
                <div class="error-message" role="alert">
                  <p>Veuillez spécifier un ID de projet ET un nombre d heures</p>
                </div>

                <?php
                $tasks = sqlQuery('SELECT T.ID as "ID", T.EMPLOYE as "EMPLOYE" , E.NOM as "NOM", T.PROJET as "PROJET", E.NOM_FONCTION as "FONCTION", F.TAUX_HORAIRE as "TAUX_HORAIRE", T.NB_HEURES as "NB_HEURES", F.TAUX_HORAIRE * T.NB_HEURES as "COUT" FROM Tache T, Employe E, Fonction F WHERE T.EMPLOYE = E.NO AND E.NOM_FONCTION = F.NOM', $db);
                $columns = array(array('ID'),array("EMPLOYE"),array("NOM"),array("PROJET"),array("FONCTION"),array("TAUX_HORAIRE"),array("NB_HEURES"),array("COUT"));
                printTable($tasks, $columns);
              
                elseif(isset($_GET["projet"]) && ($_GET["projet"] != "NULL")):
                $tasks = sqlQuery('SELECT T.ID as "ID", T.EMPLOYE as "EMPLOYE" , E.NOM as "NOM", T.PROJET as "PROJET", E.NOM_FONCTION as "FONCTION", F.TAUX_HORAIRE as "TAUX_HORAIRE", T.NB_HEURES as "NB_HEURES", F.TAUX_HORAIRE * T.NB_HEURES as "COUT" FROM Tache T, Employe E, Fonction F WHERE T.EMPLOYE = E.NO AND E.NOM_FONCTION = F.NOM AND T.PROJET = "' . $_GET["projet"] . '"', $db);
                $columns = array(array('ID'),array("EMPLOYE"),array("NOM"),array("PROJET"),array("FONCTION"),array("TAUX_HORAIRE"),array("NB_HEURES"),array("COUT"));
                printTable($tasks, $columns);

              else:
                $tasks = sqlQuery('SELECT T.ID as "ID", T.EMPLOYE as "EMPLOYE" , E.NOM as "NOM", T.PROJET as "PROJET", E.NOM_FONCTION as "FONCTION", F.TAUX_HORAIRE as "TAUX_HORAIRE", T.NB_HEURES as "NB_HEURES", F.TAUX_HORAIRE * T.NB_HEURES as "COUT" FROM Tache T, Employe E, Fonction F WHERE T.EMPLOYE = E.NO AND E.NOM_FONCTION = F.NOM', $db);
                $columns = array(array('ID'),array("EMPLOYE"),array("NOM"),array("PROJET"),array("FONCTION"),array("TAUX_HORAIRE"),array("NB_HEURES"),array("COUT"));
                printTable($tasks, $columns);
              endif;
        ?>
      </div>
    </div>
  </body>

</html>