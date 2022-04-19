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
    <title>Database - Add data</title>
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
        <p>Choisissez la table à laquelle ajouter des données : </p>
        <form action ='/add.php' method = 'GET'>
          <select name="table" id="table-select">
              <option value="NULL">---</option>
              <?php
                  $tables = sqlQuery('SHOW TABLES',$db);
                  foreach ($tables as $table) : 
              ?>
                  <option name ="table" value="<?php echo($table["Tables_in_group25"])?>"><?php echo($table["Tables_in_group25"])?> </option>
              <?php endforeach;?>
          </select>
        <button type="submit">Accéder</button>
        </form>
      </div>

      <div class="justify-content-center">
        <?php if(isset($_GET["table"]) && ($_GET["table"] != "NULL")):
                $tuples = sqlQuery('SELECT * FROM ' . $_GET["table"], $db);
                $columns = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $_GET["table"]. "' ORDER BY ORDINAL_POSITION", $db);
        ?>
        <form action='add.php' method = 'GET'>
          <input name="table" value="<?php echo($_GET["table"]);?>" type ="hidden">
          <input name="added" value="TRUE" type ="hidden">
       
          <?php
            foreach($columns as $column):
          ?>       

          <label for="<?php echo($column[0]);?>"><?php echo($column[0]);?></label>
          <input type="text" id="<?php echo($column[0]);?>" name="<?php echo($column[0]);?>">
          <br><br>
          <?php endforeach;?>

          <button type="submit">Ajouter</button>
        </form>
        
        <div class="table-search">
          <?php
                printTable($tuples,$columns);

                  if(isset($_GET["added"]) && isset($_GET["table"])):
                    $query = "INSERT INTO " . $_GET["table"] . " (";
                    foreach($_GET as $key => $value):
                      if($key == "table" || $key == "added"):
                        continue;
                      endif;
                      $query = $query . $key .',';
                    endforeach;
                    $query = substr_replace($query,"", -1);
                    $query = $query . ') VALUES (';
                    
                    foreach($_GET as $key => $value):
                      if($key == "table" || $key == "added"):
                        continue;
                      endif;
                      $query = $query . "'" .  $value . "'" . ',';
                    endforeach;
                    $query = substr_replace($query,"", -1);
                    $query = $query . ');';
                    sqlQuery($query, $db);
                    

                  endif;
                endif;
          ?>
        </div>
      </div>
    </div>
  </body>
</html>