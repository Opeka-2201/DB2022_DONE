<!-- © Group25 - Bases de Données 2022 : Projet 2-->

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
          $roles_query = $roles_query . "IF(E.NO IN (SELECT E.EXPERT FROM Evaluation E WHERE E.PROJET = '" . $project[0] . "'),'EXPERT ',''),";
          $roles_query = $roles_query . "IF(E.NO IN (SELECT R.EMPLOYE FROM Rapport R WHERE R.PROJET = '" . $project[0] . "'),'EMPLOYE_RAPPORT ',''),";
          $roles_query = $roles_query . "IF(E.NO IN (SELECT T.EMPLOYE FROM Tache T WHERE T.PROJET = '" . $project[0] . "'),'EMPLOYE_TACHE ',''))";
          $roles_query = $roles_query . " as '" . $project[0] . "'";
          $columns->append(array($project[0]));
        endforeach;
        $roles_query = $roles_query . " FROM Employe E) temp";

        if(isset($_POST["mode"]) && $_POST["mode"] == 'full'):
        ?>
          <br>
          <form class="d-flex justify-content-center" action="employe_project.php" method='POST'>
            <button type="submit">Passer au mode restreint</button>
          </form>
        <?php
        else:
        ?>
          <br>
          <form class="d-flex justify-content-center" action="employe_project.php" method="POST">
            <input name="mode" value="full" type="hidden">
            <button type='submit'>Passer au mode complet</button>
          </form>
        <?php
          $roles_query = $roles_query . " WHERE ";
          foreach($projects as $project):
            $roles_query = $roles_query . "`" . $project[0] . "`!= ' ' AND ";
          endforeach;
          $roles_query = $roles_query . "1";
        endif;
        echo($roles_query);
        $roles_table = sqlQuery($roles_query,$db);
    ?>
        <div class="px-4 py-5 my-5">
          <div class="justify-content-center">
            <?php printTable($roles_table,$columns); ?>
          </div>
          <br>
          <div style="margin-left:30px">
            <ul>
              <li>CHEF signifie que l'employé est chef du projet correspondant</li>
              <li>EXPERT signifie que l'employé a réalisé un rapport lié au projet correspondant</li>
              <li>EMPLOYE_TACHE signifie que l'employé à réalisé une tâche liée au projet correspondant</li>
              <li>EMPLOYE_RAPPORT signifie que l'employé à réalisé un rapport lié à sa tâche dans le projet correspondant</li>
            </ul>
          </div>
        </div>
    <?php
      else:
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>