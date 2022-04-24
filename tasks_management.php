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
    <title>Database - Tâches</title>
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
        <form action ='tasks_management.php' method = 'POST'>
          <select name="projet" id="projet-select">
              <option value=NULL>---</option>
              <?php
                $projects = sqlQuery('SELECT * FROM Projet',$db);
                foreach ($projects as $projet): 
              ?>
                <option name="projet" value="<?php echo($projet["NOM"])?>"><?php echo($projet["NOM"])?> </option>
                <?php endforeach;?>
          </select>
          <button type="submit">Accéder</button>
        </form>
      </div>

      <div class="justify-content-center">
        <?php
          if(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["task_id"]) && isset($_POST["task_hours"]) && $_POST["task_id"] != 'NULL' && $_POST["task_hours"] != ''):
            $old_hours = sqlQuery('SELECT NB_HEURES FROM Tache WHERE ID="' . $_POST["task_id"] . '"',$db);
            $new_hours = $old_hours[0][0] + intval($_POST["task_hours"]);
            if($new_hours >= $old_hours[0][0]):
              sqlQuery('UPDATE Tache SET NB_HEURES=' . $new_hours . ' WHERE ID=' . $_POST["task_id"],$db);
            else:
            ?>
              <div class="error-message" role="alert">
                  <p>Merci de fournir un nombre d'heures entier positif</p>
              </div>
            <?php
            endif;
          
          elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["add_employe"]) && $_POST["add_employe"] != NULL && isset($_POST["add_hours"]) && $_POST["add_hours"] != ''):
            if(intval($_POST["add_hours"]) > 0):
              sqlQuery('INSERT INTO Tache (EMPLOYE, PROJET, NB_HEURES) VALUES ("' . $_POST["add_employe"] . '","' . $_POST["projet"] . '",' . $_POST["add_hours"] . ')',$db);
            else:
            ?>
              <div class="error-message" role="alert">
                <p>Merci de fournir un nombre d'heures entier positif</p>
              </div>
            <?php
            endif;
          
          elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["avis_expert"])):
            $total_cost = 0;
            $tasks_cost = sqlQuery('SELECT Fonction.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN Fonction ON Employe.NOM_FONCTION = Fonction.NOM WHERE Tache.PROJET = "' . $_POST["projet"] . '"',$db);
            foreach($tasks_cost as $task_cost):
              $total_cost += intval($task_cost[0]); 
            endforeach;
            $end_date = sqlQuery('SELECT DATE(NOW())',$db);
            sqlQuery('UPDATE Projet SET COUT=' . $total_cost . ', DATE_FIN="' . $end_date[0][0]  .'", AVIS_EXPERT="' . $_POST["avis_expert"]  .'" WHERE NOM="' . $_POST["projet"] . '"', $db);
          elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["edit_opinion"])):
            if($_POST["edit_opinion"] == 'NULL'):
              sqlQuery('UPDATE Projet SET AVIS_EXPERT=' . $_POST["edit_opinion"] . ' WHERE NOM="' . $_POST["projet"] .'"' ,$db);
            else:
              sqlQuery('UPDATE Projet SET AVIS_EXPERT="' . $_POST["edit_opinion"] . '" WHERE NOM="' . $_POST["projet"] .'"' ,$db);
            endif;
          endif;

          if(isset($_POST["projet"]) && $_POST["projet"] == 'NULL'):

          elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL'):
            ?>
            <br>
            <p>Détails concernant le projet <?php echo($_POST["projet"]);?> :</p>
            <?php
            $date_fin = sqlQuery('SELECT DATE_FIN FROM Projet WHERE Projet.NOM = "' . $_POST["projet"] . '"', $db);
            if(isset($date_fin[0]) && $date_fin[0][0] != NULL):
              $project_details = sqlQuery('SELECT * FROM Projet WHERE Projet.NOM = "' . $_POST["projet"] . '"',$db);
              $columns = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Projet' ORDER BY ORDINAL_POSITION",$db);
              printTableCost($project_details, $columns);

              echo('<br><br><p>Liste des tâches du projet ' . $_POST["projet"] . ' : </p>');
              $tasks = sqlQuery("SELECT Tache.ID, Tache.PROJET, Tache.EMPLOYE, Employe.NOM, Tache.NB_HEURES, Employe.NOM_FONCTION, Fonction.TAUX_HORAIRE, Fonction.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN Fonction ON Employe.NOM_FONCTION = Fonction.NOM WHERE Tache.PROJET = '" . $_POST["projet"] . "'", $db);
              $columns = array(array("ID"),array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
              printTable($tasks, $columns);

              ?>
              <br>
              <p>Modification de l'avis de l'expert pour le projet <?php echo($_POST["projet"]);?> :</p>

              <form action='tasks_management.php' method='POST'>
                <input name="projet" value="<?php echo($_POST["projet"]);?>" type ="hidden">
                <?php echo("Avis de l'expert :"); ?>
                <select name='edit_opinion'>
                  <option value=NULL>---</option>
                  <option value='SUCCÈS'>SUCCÈS</option>
                  <option value='ÉCHEC'>ÉCHEC</option>
                </select>
                <br><br>
                <button type='submit'>Modifier l'avis d'expert</button>
              </form>
              <?php

            else:
              $project_details = sqlQuery('SELECT * FROM Projet WHERE Projet.NOM = "' . $_POST["projet"] . '"',$db);
              $columns = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Projet' ORDER BY ORDINAL_POSITION",$db);
              printTable($project_details, $columns);

              echo('<br><br><p>Liste des tâches du projet ' . $_POST["projet"] . ' : </p>');
              $tasks = sqlQuery("SELECT Tache.ID, Tache.PROJET, Tache.EMPLOYE, Employe.NOM, Tache.NB_HEURES, Employe.NOM_FONCTION, Fonction.TAUX_HORAIRE, Fonction.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN Fonction ON Employe.NOM_FONCTION = Fonction.NOM WHERE Tache.PROJET = '" . $_POST["projet"] . "'", $db);
              $columns = array(array("ID"),array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
              printTable($tasks, $columns);
              
              $task_ids = sqlQuery('SELECT ID FROM Tache WHERE Tache.PROJET ="' . $_POST["projet"] . '"',$db);
              $employes_not_in_project = sqlQuery("SELECT NO FROM Employe WHERE Employe.NO NOT IN (SELECT EMPLOYE FROM Tache WHERE Tache.PROJET='" . $_POST["projet"]  . "')", $db);
              ?>
              <div class="d-flex justify-content-between">
                <div>
                  <br><br><p>Soumission des heures pour les tâches du projet <?php echo($_POST["projet"]);?> : </p>
                  <form action ='tasks_management.php' method = 'POST'>
                    <input name="projet" value="<?php echo($_POST["projet"]);?>" type ="hidden">
                    <?php echo("ID Tâche : ");
                      echo("<select name='task_id'>");
                      echo("<option value=NULL>---</option>");
                      foreach($task_ids as $task_id):
                        echo("<option value='" . $task_id[0] . "'>" . $task_id[0]  . "</option>");
                      endforeach;
                      echo("</select>");
                    ?>
                    <br><br>
                    <label for="task_hours">Heures à ajouter : </label>
                    <input type="text" id="task_hours" name="task_hours">
                    <br><br>

                    <button type="submit">Soumettre heures</button>
                    <br><br>
                  </form>
                </div>

                <div>
                  <br><br><p>Ajout de tâches pour le projet  <?php echo($_POST["projet"]);?> : </p>
                  <form action='tasks_management.php' method='POST'>
                    <input name="projet" value="<?php echo($_POST["projet"]);?>" type ="hidden">
                    <?php
                      echo("Employés disponibles : ");
                      echo("<select name='add_employe'>");
                      foreach($employes_not_in_project as $employe_not_in_project):
                        echo("<option value='" . $employe_not_in_project[0] . "'>" . $employe_not_in_project[0]  . "</option>");
                      endforeach;
                      echo("</select>");
                    ?>
                    <br><br>
                    <label for="add_hours">Heures nouvelle tâche : </label>
                    <input type='text' id='add_hours' name='add_hours'>
                    <br><br>
                    <button type='submit'>Créer tâche</button>
                  </form>
                </div>

                <div>
                  <br><br><p>Formulaire de fin de projet <?php echo($_POST["projet"]);?> : </p>
                  <form action='tasks_management.php' method='POST'>
                    <input name="projet" value="<?php echo($_POST["projet"]);?>" type ="hidden">
                    <?php echo("Avis expert : "); ?>
                    <select name='avis_expert'>
                      <option value=NULL>---</option>
                      <option value='SUCCÈS'>SUCCÈS</option>
                      <option value='ÉCHEC'>ÉCHEC</option>
                    </select>
                    <br><br>
                    <button type='submit'>Mettre fin au projet</button>
                  </form>
                </div>
              </div>
              <?php
            endif;
          endif;
        ?>
      </div>
  </body>

</html>