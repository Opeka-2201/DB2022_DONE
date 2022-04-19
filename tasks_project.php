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
      <div class="justify-content-center text-center">
        <?php if(isset($_GET["projet"]) && ($_GET["projet"] != "NULL")):
                $projet = sqlQuery('SELECT * FROM Projet WHERE NOM="' . $_GET["projet"] . '"', $db);
                $tasks = sqlQuery('SELECT * FROM Tache WHERE PROJET="' . $_GET["projet"] . '"', $db);
                
                $columns = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Tache' ORDER BY ORDINAL_POSITION", $db);

                printTable($tasks,$columns);
              // SELECT Tache.EMPLOYE, Employe.NOM FROM Tache INNER JOIN Employe ON Tache.EMPLOYE = Employe.NO;
              // SELECT Employe.NOM, Fonction.TAUX_HORAIRE FROM Fonction INNER JOIN Employe ON FONCTION.NOM = Employe.NOM_FONCTION;

              // SELECT Employe.NO, Employe.NOM, Fonction.TAUX_HORAIRE FROM Fonction INNER JOIN Employe ON FONCTION.NOM = Employe.NOM_FONCTION;
              endif;
        ?>
      </div>
  </body>

</html>