<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8" />
    <title>Database - Employés/Projets</title>
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

      $emps = sqlQuery('SELECT NO FROM Employe',$db);
      foreach($emps as $emp):
        $currentEmp = $emp[0];
        $query = "SELECT PROJET FROM Tache WHERE EMPLOYE=" . $currentEmp ." UNION SELECT PROJET FROM Rapport WHERE EXPERT=" . $currentEmp ." UNION SELECT NOM FROM Projet WHERE CHEF =" . $currentEmp;
        $projectsOfEmp = sqlQuery($query,$db);
        $projects_db = sqlQuery('SELECT NOM FROM Projet',$db);
        $isEmpOK = true;
        if(cmpTable($projects_db,$projectsOfEmp)):
          echo($currentEmp . "<br>");
        endif;
      endforeach;
    ?>
  </body>

</html>