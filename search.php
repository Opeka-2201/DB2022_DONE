<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8" />
    <title>Database - Tri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <?php
      // Début session pour vérifier connexion
      session_start();
      if(isset($_SESSION['user'])):
        // si connecté entre sur le site
        require __DIR__ . '/functions.php';
        include("header.php");
        include("PDO.php");

    ?>
        <div class="px-4 py-5 my-5">
          <div class="d-flex justify-content-center">
            <p>Choisissez une table : </p>
            <form action ='search.php' method = 'POST'>
              <select name="table" id="table-select">
                  <option value="NULL">---</option>
                  <?php
                      $tables = sqlQuery('SHOW TABLES',$db);
                      foreach ($tables as $table) : 
                  ?>
                      <option name="table" value="<?php echo($table["Tables_in_group25"])?>"><?php echo($table["Tables_in_group25"])?></option>
                  <?php endforeach;?>
              </select>
            <button type="submit">Accéder</button>
            </form>
          </div>
          <div class="justify-content-center">
            <?php if(isset($_POST["table"]) && ($_POST["table"] != "NULL")):
                    $tuples = sqlQuery('SELECT * FROM ' . $_POST["table"], $db);
                    $columns = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $_POST["table"]. "' ORDER BY ORDINAL_POSITION", $db);
            ?>
            <form action='search.php' method = 'POST'>
              <br><br>
              <p>Filtre sur la table <?php echo($_POST["table"]);?> :</p>
              <input name="table" value="<?php echo($_POST["table"]);?>" type ="hidden">
            <?php
                  // Génération des champs permettant de filtrer les données en fonction de la table choisie
                  foreach($columns as $column):
            ?>       
                    <label for="<?php echo($column[0]);?>"><?php echo($column[0]);?></label>
                    <input type="text" id="<?php echo($column[0]);?>" name="<?php echo($column[0]);?>">
                    <br><br>
                <?php endforeach;?>
            <button type="submit">Filtrer</button>
            <br><br>
            </form>
            <?php
                  // Génération de la table avec les données filtrées
                  printTable(filterData($_POST,$db),$columns);
                  endif;
            ?>
          </div>
        </div>
    <?php
      else:
        // si non connecté est renvoyé vers login.php
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>