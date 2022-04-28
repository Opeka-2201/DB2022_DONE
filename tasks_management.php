<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8" />
    <title>Database - Tâches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <?php
      session_start();
      if(isset($_SESSION['user'])):
        require __DIR__ . '/functions.php';
        include("header.php");
        include("PDO.php");

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
              
              elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["add_employe"]) && $_POST["add_employe"] != NULL):
                if(intval($_POST["add_hours"]) > 0):
                  sqlQuery('INSERT INTO Tache (EMPLOYE, PROJET, NB_HEURES) VALUES ("' . $_POST["add_employe"] . '","' . $_POST["projet"] . '",' . $_POST["add_hours"] . ')',$db);
                elseif($_POST["add_hours"] =' '):
                  sqlQuery('INSERT INTO Tache (EMPLOYE, PROJET) VALUES ("' . $_POST["add_employe"] . '","' . $_POST["projet"] . '")',$db);
                else:
                ?>
                  <div class="error-message" role="alert">
                    <p>Merci de fournir un nombre d'heures entier positif</p>
                  </div>
                <?php
                endif;
              
              elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["expert"]) && isset($_POST["commentaires"]) && isset($_POST["avis_expert"])):
                $total_cost = 0;
                $tasks_cost = sqlQuery('SELECT Fonction.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN Fonction ON Employe.NOM_FONCTION = Fonction.NOM WHERE Tache.PROJET = "' . $_POST["projet"] . '"',$db);
                foreach($tasks_cost as $task_cost):
                  $total_cost += intval($task_cost[0]); 
                endforeach;
                $end_date = sqlQuery('SELECT DATE(NOW())',$db);
                sqlQuery('UPDATE Projet SET COUT=' . $total_cost . ', DATE_FIN="' . $end_date[0][0]  .'" WHERE NOM="' . $_POST["projet"] . '"', $db);
                if(isset($_POST["eval?"]) && $_POST["eval?"] == 'yes'):
                  sqlQuery('INSERT INTO Evaluation (PROJET,EXPERT,COMMENTAIRES,AVIS) VALUES ("' . $_POST["projet"]  . '","' . $_POST["expert"] . '","' . $_POST["commentaires"] . '","' . $_POST["avis_expert"] . '")',$db);
                endif;
              elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["edit_opinion"])):
                if($_POST["edit_opinion"] == 'NULL'):
                  sqlQuery('UPDATE Evaluation SET AVIS=' . $_POST["edit_opinion"] . ' WHERE PROJET="' . $_POST["projet"] .'"' ,$db);
                else:
                  sqlQuery('UPDATE Evaluation SET AVIS="' . $_POST["edit_opinion"] . '" WHERE PROJET="' . $_POST["projet"] .'"' ,$db);
                endif;
              elseif(isset($_POST["projet"]) && $_POST["projet"] != 'NULL' && isset($_POST["report_task"]) && isset($_POST["report_title"]) && isset($_POST["report_keywords"])):
                if($_POST["report_title"]==NULL || $_POST["report_keywords"]==NULL):
                  ?>
                  <div class="error-message" role="alert">
                    <p>Veuillez fournir un titre de rapport ET au minimum un mot clé</p>
                  </div>
                  <?php
                else:
                  $employe_task = sqlQuery('SELECT EMPLOYE FROM Tache WHERE Tache.ID=' . $_POST["report_task"],$db);
                  $project_task = sqlQuery('SELECT PROJET FROM Tache WHERE Tache.ID=' . $_POST["report_task"],$db);
                  sqlQuery('INSERT INTO Rapport (EMPLOYE,PROJET,TITRE) VALUES (' . $employe_task[0][0] . ',"' . $project_task[0][0]  . '","' . $_POST["report_title"]  . '")',$db);

                  $keywords = explode(" ",$_POST["report_keywords"]);
                  foreach($keywords as $keyword):
                    sqlQuery('INSERT INTO Mots_Cles (RAPPORT,MOT_CLE) VALUES ("' . $_POST["report_title"]  . '","' . $keyword  . '")',$db);
                  endforeach;
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
                  
                  $check_eval = sqlQuery('SELECT EXISTS(SELECT * FROM Evaluation E WHERE E.PROJET ="' . $_POST["projet"]  . '")',$db);
                  if($check_eval[0][0]):
                    $project_details = sqlQuery('SELECT P.*, E.AVIS FROM Projet P INNER JOIN Evaluation E ON P.NOM = E.PROJET WHERE P.NOM = "' . $_POST["projet"] . '"',$db);
                    $columns = array(array("NOM"),array("DEPARTEMENT"),array("DATE_DEBUT"),array("CHEF"),array("BUDGET"),array("COUT"),array("DATE_FIN"),array("AVIS"));
                    printTableCost($project_details, $columns);
                  else:
                    $project_details = sqlQuery('SELECT P.* FROM Projet P WHERE P.NOM = "' . $_POST["projet"] . '"',$db);
                    $columns = array(array("NOM"),array("DEPARTEMENT"),array("DATE_DEBUT"),array("CHEF"),array("BUDGET"),array("COUT"),array("DATE_FIN"));
                    printTableCost($project_details, $columns);
                  endif;

                  echo('<br><br><p>Liste des tâches du projet ' . $_POST["projet"] . ' : </p>');
                  $tasks = sqlQuery('SELECT Tache.ID, Tache.PROJET, Tache.EMPLOYE, Employe.NOM, Tache.NB_HEURES, Employe.NOM_FONCTION, temp.TAUX_HORAIRE, temp.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN (SELECT * FROM Fonction UNION SELECT " " as NOM, 0 as TAUX_HORAIRE) temp ON COALESCE(Employe.NOM_FONCTION," ") = temp.NOM WHERE Tache.PROJET = "' . $_POST["projet"] . '"', $db);
                  $columns = array(array("ID"),array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
                  printTable($tasks, $columns);

                  ?>
                  <br>
                  <div class="d-flex justify-content-around">
                    <div>
                      <?php if($check_eval[0][0]):?>
                      <p>Modification de l'avis de l'expert pour le projet <?php echo($_POST["projet"]);?> :</p>
                      
                      <form action='tasks_management.php' method='POST'>
                        <input name="projet" value="<?php echo($_POST["projet"]);?>" type ="hidden">
                        <?php echo("Avis de l'expert :"); ?>
                        <select name='edit_opinion'>
                          <option value=NULL>---</option>
                          <option value='SUCCES'>SUCCES</option>
                          <option value='ECHEC'>ECHEC</option>
                        </select>
                        <br><br>
                        <button type='submit'>Modifier l'avis d'expert</button>
                      </form>
                    </div>
                  <?php endif;

                else:
                  $project_details = sqlQuery('SELECT P.NOM, P.DEPARTEMENT, P.DATE_DEBUT, P.CHEF, P.BUDGET FROM Projet P WHERE P.NOM = "' . $_POST["projet"] . '"',$db);
                  $columns = array(array("NOM"),array("DEPARTEMENT"),array("DATE_DEBUT"),array("CHEF"),array("BUDGET"));
                  printTable($project_details, $columns);

                  echo('<br><br><p>Liste des tâches du projet ' . $_POST["projet"] . ' : </p>');
                  $tasks = sqlQuery('SELECT Tache.ID, Tache.PROJET, Tache.EMPLOYE, Employe.NOM, Tache.NB_HEURES, Employe.NOM_FONCTION, temp.TAUX_HORAIRE, temp.TAUX_HORAIRE*Tache.NB_HEURES FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN (SELECT * FROM Fonction UNION SELECT " " as NOM, 0 as TAUX_HORAIRE) temp ON COALESCE(Employe.NOM_FONCTION," ") = temp.NOM WHERE Tache.PROJET = "' . $_POST["projet"] . '"', $db);
                  $columns = array(array("ID"),array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
                  printTable($tasks, $columns);
                  
                  $task_ids = sqlQuery('SELECT ID FROM Tache WHERE Tache.PROJET ="' . $_POST["projet"] . '"',$db);
                  $employes_not_in_project = sqlQuery("SELECT NO FROM Employe WHERE Employe.NO NOT IN (SELECT EMPLOYE FROM Tache WHERE Tache.PROJET='" . $_POST["projet"]  . "')", $db);
                  ?>
                  <div class="d-flex justify-content-around">
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
                    <?php if(!empty($employes_not_in_project)): ?>
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
                    <?php endif; ?>

                    <div>
                      <br><br><p>Formulaire de fin de projet <?php echo($_POST["projet"]);?> : </p>
                      <form action='tasks_management.php' method='POST'>
                        <input name="projet" value="<?php echo($_POST["projet"]);?>" type ="hidden">
                        <?php echo("Créer évalutation ? : "); ?>
                        <input type='checkbox' value='yes' name='eval?' id ='eval?'>
                        <br><br>
                        <?php echo("Expert : ");?>
                        <select name="expert" id="expert">
                            <?php
                              $employes = sqlQuery('SELECT * FROM Employe',$db);
                              foreach ($employes as $employe): 
                            ?>
                              <option name="expert" value="<?php echo($employe["NO"])?>"><?php echo($employe["NO"])?> </option>
                              <?php endforeach;?>
                        </select>
                        <?php echo("<br><br>Commentaires : ");?>
                        <input type='text' id='commentaires' name='commentaires'>
                        <br><br>
                        <?php echo("Avis expert : "); ?>
                        <select name='avis_expert'>
                          <option value=NULL>---</option>
                          <option value='SUCCES'>SUCCES</option>
                          <option value='ECHEC'>ECHEC</option>
                        </select>
                        <br><br>
                        <button type='submit'>Mettre fin au projet</button>
                      </form>
                    </div>
                  </div>
                  <?php
                endif;
                $tasks_in_project_with_no_report = sqlQuery('SELECT T.ID FROM Tache T WHERE T.ID NOT IN (SELECT A.ID as ID2 FROM Tache A INNER JOIN Rapport R ON (A.EMPLOYE = R.EMPLOYE) WHERE A.PROJET="' . $_POST["projet"]  . '" AND R.PROJET="' . $_POST["projet"]  . '") AND T.PROJET="' . $_POST["projet"]  . '"',$db);
                
                if(!empty($tasks_in_project_with_no_report)):
                ?>
                  <div class="d-flex justify-content-around">
                    <form action='tasks_management.php' method='POST'>
                      <p>Création d'un rapport pour les tâches du projet <?php echo($_POST["projet"]); ?> : </p>
                      <input name="projet" value="<?php echo($_POST["projet"]);?>" type ="hidden">
                      <?php echo('ID des tâches sans rapport :');?>
                      <select name='report_task'>
                        <?php
                          foreach($tasks_in_project_with_no_report as $task_in_project_with_no_report):
                            echo("<option value='" . $task_in_project_with_no_report[0] . "'>" . $task_in_project_with_no_report[0]  . "</option>");
                          endforeach;
                        ?>
                      </select>
                      <br><br>
                      <label for="report_title">Titre du rapport : </label>
                      <input type="text" id="report_title" name="report_title">
                      <br><br>
                      <label for="report_keywords">Mots clés du rapport (à séparer par un espace) : </label>
                      <input type="text" id="report_keywords" name="report_keywords">
                      <br><br>
                      <button type='submit'>Créer rapport</button>
                    </form>
                  </div>
                <?php
                endif;
              endif;
            ?>
          </div>
    <?php
      else:
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>