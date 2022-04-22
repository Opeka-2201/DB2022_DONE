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
    <title>Database - Ajout</title>
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
              <option value="Projet">Projet</option>
              <option value="Fonction">Fonction</option>
              <option value="Employe">Employe</option>
          </select>
          
          <button type="submit">Accéder</button>
        </form>
      </div>

      <div class="justify-content-center">
        <?php if(isset($_GET["table"]) && ($_GET["table"] != "NULL")):
                $tuples = sqlQuery('SELECT * FROM ' . $_GET["table"], $db);
                $columns = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $_GET["table"]. "' ORDER BY ORDINAL_POSITION", $db);
        ?>
        <p>Ajout d'un élément dans la table <?php echo($_GET["table"]);?> :</p>
        <form action='add.php' method = 'GET'>
          <input name="table" value="<?php echo($_GET["table"]);?>" type ="hidden">
          <input name="added" value="TRUE" type ="hidden">
       
          <?php
            foreach($columns as $column):
              if($_GET["table"] == "Projet" && $column[0] == 'CHEF'):
                $chefs = sqlQuery('SELECT NOM FROM Employe WHERE NOM_DEPARTEMENT != "NULL" AND NOM_FONCTION != "NULL"',$db);
                echo("CHEF ");
                echo("<select name='CHEF'>");
                foreach ($chefs as $chef):
                  $id = sqlQuery('SELECT NO FROM Employe WHERE NOM="' . $chef[0] .'"', $db);
                  echo("<option value=" . $id[0][0] . ">" . $chef[0] . "</option>");
                endforeach;
                echo("</select>");
              elseif($_GET["table"] == "Projet" && $column[0] == 'DEPARTEMENT'):
                $departements = sqlQuery('SELECT NOM FROM DEPARTEMENT',$db);
                echo("DEPARTEMENT ");
                echo("<select name='DEPARTEMENT'>");
                foreach ($departements as $departement):
                  echo("<option value=" . $departement[0] . ">" . $departement[0] . "</option>");
                endforeach;
                echo("</select>");
              elseif($_GET["table"] == "Projet" && $column[0] == 'AVIS_EXPERT'):
                echo('AVIS_EXPERT :')
              ?>
                <select name="AVIS_EXPERT">
                  <option value=NULL>---</option>
                  <option value='SUCCÈS'>SUCCÈS</option>
                  <option value='ÉCHEC'>ÉCHEC</option>
                </select>
              <?php
              elseif($_GET["table"] == "Employe" && $column[0] == "NOM_DEPARTEMENT"):
                $departements = sqlQuery('SELECT NOM FROM Departement', $db);
                echo("NOM_DEPARTEMENT ");
                echo("<select name='NOM_DEPARTEMENT'>");
                echo("<option value=NULL>---</option>");
                foreach ($departements as $departement):
                echo("<option value='" . $departement[0] . "'>" . $departement[0] . "</option>");
                endforeach;
                echo("</select>");
              
              elseif($_GET["table"] == "Employe" && $column[0] == "NOM_FONCTION"):
                $fonctions = sqlQuery('SELECT NOM FROM Fonction', $db);
                echo("NOM_FONCTION ");
                echo("<select name='NOM_FONCTION'>");
                echo("<option value=NULL>---</option>");
                foreach ($fonctions as $fonction):
                echo("<option value='" . $fonction[0] . "'>" . $fonction[0] . "</option>");
                endforeach;
                echo("</select>");

              else:
          ?>
          <label for="<?php echo($column[0]);?>"><?php echo($column[0]);?></label>
          <input type="text" id="<?php echo($column[0]);?>" name="<?php echo($column[0]);?>">
          <?php 
              endif;
              echo("<br><br>");
              endforeach;?>

          <button type="submit">Ajouter</button>
        </form>
        <br>
        <?php 
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
              elseif($value == 'NULL' || $value == ''):
                $query = $query .' NULL,';
                continue;
              elseif(strpos(" " . $key, 'DATE') != false):
                $query = $query . "str_to_date('" . $value . "', '%Y-%m-%d'),";
                continue;
              else:
              $query = $query . "'" .  $value . "'" . ',';
              endif;
            endforeach;
            $query = substr_replace($query,"", -1);
            $query = $query . ');';
            sqlQuery($query, $db);
          
          elseif(isset($_GET["edit_employe"]) && isset($_GET["table"])):
            if($_GET["departement"]=='NULL' && $_GET["fonction"]=='NULL'):
              sqlQuery("UPDATE Employe SET NOM_DEPARTEMENT=NULL, NOM_FONCTION=NULL WHERE NO=" . $_GET["employe_id"],$db);
            elseif($_GET["departement"]=='NULL'):
              sqlQuery("UPDATE Employe SET NOM_DEPARTEMENT=NULL, NOM_FONCTION='" . $_GET["fonction"] . "' WHERE NO=" . $_GET["employe_id"],$db);
            elseif($_GET["fonction"]=='NULL'):
              sqlQuery("UPDATE Employe SET NOM_DEPARTEMENT='" . $_GET["departement"] . "', NOM_FONCTION=NULL WHERE NO=" . $_GET["employe_id"],$db);
            else:
              sqlQuery("UPDATE Employe SET NOM_DEPARTEMENT='" . $_GET["departement"] . "', NOM_FONCTION='" . $_GET["fonction"] . "' WHERE NO=" . $_GET["employe_id"],$db);
            endif;
          
          elseif(isset($_GET["edit_projet"]) && isset($_GET["table"]) && $_GET["edited_projet"] != 'NULL'):
            if($_GET["budget_projet"] != NULL):
              echo("UPDATE Projet SET BUDGET=" . $_GET["budget_projet"]  . " WHERE NOM='" . $_GET["edited_projet"] . "'");
              sqlQuery("UPDATE Projet SET BUDGET=" . $_GET["budget_projet"]  . " WHERE NOM='" . $_GET["edited_projet"] . "'",$db);
            else:
              echo("UPDATE Projet SET BUDGET=NULL WHERE NOM='" . $_GET["edited_projet"] . "'");
              sqlQuery("UPDATE Projet SET BUDGET=NULL WHERE NOM='" . $_GET["edited_projet"] . "'",$db);
            endif;
          endif;
          $tuples = sqlQuery('SELECT * FROM ' . $_GET["table"], $db);
          $columns = sqlQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $_GET["table"]. "' ORDER BY ORDINAL_POSITION", $db);
          printTable($tuples,$columns); 
        endif;?>
      </div>
      
      <?php
        if(isset($_GET["table"]) && $_GET["table"]=='Employe'):
          $employes = sqlQuery('SELECT NO FROM Employe',$db);
          $departements = sqlQuery('SELECT NOM FROM Departement',$db);
          $fonctions = sqlQuery('SELECT NOM FROM Fonction',$db);
      ?>
          <div class="justify-content-center">
            <br>
            <p>Modification des attributs d'un employé :</p>
            <form action='add.php' method='GET'>
              <input name="table" value="<?php echo($_GET["table"]);?>" type ="hidden">
              <input name="edit_employe" value="TRUE" type="hidden">
              <?php
                echo("NO :");
                echo("<select name='employe_id'>");
                echo("<option value=NULL>---</option>");
                foreach($employes as $employe):
                  echo("<option value='" . $employe[0] . "'>" . $employe[0]  . "</option>");
                endforeach;
                echo("</select>");
                echo("<br><br>DEPARTEMENT :");
                echo("<select name='departement'>");
                echo("<option value=NULL>---</option>");
                foreach($departements as $departement):
                  echo("<option value='" . $departement[0] . "'>" . $departement[0]  . "</option>");
                endforeach;
                echo("</select>");
                echo("<br><br>FONCTION :");
                echo("<select name='fonction'>");
                echo("<option value=NULL>---</option>");
                foreach($fonctions as $fonction):
                  echo("<option value='" . $fonction[0] . "'>" . $fonction[0]  . "</option>");
                endforeach;
                echo("</select>");
                echo("<br><br>");
              ?>
              <button type='submit'>Modifier employé</button>
            </form>
          </div>
      <?php
        elseif(isset($_GET["table"]) && $_GET["table"]=='Projet'):
          $projets = sqlQuery('SELECT NOM FROM Projet',$db);
      ?>
          <div class="justify-content-center">
            <br>
            <p>Modification du budget d'un projet :</p>
            <form action='add.php' method='GET'>
              <input name="table" value="<?php echo($_GET["table"]);?>" type ="hidden">
              <input name="edit_projet" value="TRUE" type="hidden">
              <?php
                echo("PROJET :");
                echo("<select name='edited_projet'>");
                echo("<option value=NULL>---</option>");
                foreach($projets as $projet):
                  echo("<option value='" . $projet[0] . "'>" . $projet[0]  . "</option>");
                endforeach;
                echo("</select>");
              ?>
              <br><br>
              <label for="budget_projet">Nouveau budget :</label>
              <input type="text" id="budget_projet" name="budget_projet">
              <br><br>
              <button type='submit'>Mettre à jour le budget</button>
            </form>
          </div>
      <?php
        endif;
      ?>
    </div>
  </body>
</html>