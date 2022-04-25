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
    <title>Database - Employés/Projets</title>
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

        $projects = sqlQuery('SELECT NOM FROM Projet',$db);
        $columns = new ArrayObject(array(array("NO"),array("NOM"),array("NOM_FONCTION")));
        $roles_query = "SELECT * FROM (SELECT E.NO, E.NOM, E.NOM_FONCTION";
        foreach($projects as $project):
          $roles_query = $roles_query . ", CONCAT(IF(E.NO IN (SELECT P.CHEF FROM Projet P WHERE P.NOM = '" . $project[0] . "'), 'CHEF ',''),";
          $roles_query = $roles_query . "IF(E.NO IN (SELECT R.EXPERT FROM Rapport R WHERE R.PROJET = '" . $project[0] . "'),'EXPERT ',''),";
          $roles_query = $roles_query . "IF(E.NO IN (SELECT T.EMPLOYE FROM Tache T WHERE T.PROJET = '" . $project[0] . "'),'EMPLOYE ',''))";
          $roles_query = $roles_query . " as '" . $project[0] . "'";
          $columns->append(array($project[0]));
        endforeach;
        $roles_query = $roles_query . " FROM Employe E) temp WHERE ";
        foreach($projects as $project):
          $roles_query = $roles_query . "`" . $project[0] . "`!= ' ' AND ";
        endforeach;
        $roles_query = $roles_query . "1";
        $roles_table = sqlQuery($roles_query,$db);
    ?>
        <div class="px-4 py-5 my-5">
          <div class="justify-content-center">
            <?php printTable($roles_table,$columns); ?>
          </div>
        </div>
        <div style="margin-left:30px">
          <ul>
            <li>CHEF signifie que l'employé est chef du projet correspondant</li>
            <li>EXPERT signifie que l'employé a réalisé un rapport lié au projet correspondant</li>
            <li>EMPLOYE signifie que l'employé à réalisé une tâche liée au projet correspondant</li>
          </ul>
        </div>
    <?php
      else:
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>