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
        <form action ='tasks_project.php' method = 'POST'>
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
        <br><br><br>
      </div>



      <div class="justify-content-center">
        <?php 
          if(isset($_POST["projet"]) && ($_POST["projet"] != "NULL")):
          ?>
            <p>Tâches relatives au projet <?php echo($_POST["projet"]);?> :</p>
          <?php
            $tasks = sqlQuery('SELECT T.PROJET, T.EMPLOYE, E.NOM, T.NB_HEURES, E.NOM_FONCTION, F.TAUX_HORAIRE, F.TAUX_HORAIRE*T.NB_HEURES FROM Employe E INNER JOIN Tache T ON T.EMPLOYE = E.NO INNER JOIN Fonction F ON E.NOM_FONCTION = F.NOM WHERE T.PROJET = "' . $_POST["projet"] . '"', $db);
            $columns = array(array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
            printTable($tasks, $columns);

          else:
            $tasks = sqlQuery('SELECT T.PROJET, T.EMPLOYE, E.NOM, T.NB_HEURES, E.NOM_FONCTION, F.TAUX_HORAIRE, F.TAUX_HORAIRE*T.NB_HEURES FROM Employe E INNER JOIN Tache T ON T.EMPLOYE = E.NO INNER JOIN Fonction F ON E.NOM_FONCTION = F.NOM', $db);
            $columns = array(array("PROJET"),array("EMPLOYE"),array("NOM"),array("NB_HEURES"),array("NOM_FONCTION"),array("TAUX_HORAIRE"),array("COUT"));
            printTable($tasks, $columns);
          endif;
        ?>
      </div>
    </div>
    <?php
      else:
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>