<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<style>
      table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
      }

      td, th {
        text-align: left;
        padding: 8px;
      }

      tr:nth-child(even) {
        background-color: rgba(230,230,230,0.5);
      }
</style>

<?php
// Différentes fonctions nécessaires au bon fonctionnement du site

// Fonction permettant d'afficher les tables
function printTable($tuples, $columns){
    echo("<table>");
    echo("<tr>");
    foreach($columns as $column):
        echo("<th>" . $column[0] . "</th>");
    endforeach;
    echo("</tr>");
    foreach ($tuples as $tuple) :
        echo("<tr>");
        for($i=0;$i<count($tuple)/2;$i++):
            echo("<td>" . $tuple[$i] . "</td>");
        endfor;
        echo("</tr>");
    endforeach;
    echo("</table>");
}

// Fonction affichant la table des projets avec un code couleur pour le cout dans le cas de l'onglet Taches
function printTableCost($tuples, $columns){
  echo("<table>");
  echo("<tr>");
  foreach($columns as $column):
      echo("<th>" . $column[0] . "</th>");
  endforeach;
  echo("</tr>");
  foreach ($tuples as $tuple) :
      echo("<tr>");
      for($i=0;$i<count($tuple)/2;$i++):
        if($i != 5):
          echo("<td>" . $tuple[$i] . "</td>");
        else:
          if($tuple[$i] <= $tuple[$i-1]):
           echo('<td style="color:green">' . $tuple[$i] . '</td>');
          elseif(($tuple[$i-1] < $tuple[$i]) && ($tuple[$i] <= 1.1*$tuple[$i-1])):
           echo('<td style="color:orange">' . $tuple[$i] . '</td>');
          else:
           echo('<td style="color:red">' . $tuple[$i] . '</td>');
          endif;
        endif;
      endfor;
      echo("</tr>");
  endforeach;
  echo("</table>");
}

// Fonction simplifiant les requêtes en MySQL
function sqlQuery($query, $db){
  try {
    if($db->inTransaction()):
      die("Base de données déjà en transaction");
    else:
      $db->beginTransaction();
      $statement = $db->prepare($query);
      $statement->execute();
      $db->commit();
      return $statement->fetchAll();
    endif;
  } catch (\PDOException $e) {
    $db->rollBack();
    die($e->getMessage());
  }
}

// Fonction permettant le filtrage de data dans l'onglet Tri
function filterData($args_get,$db){
    $query = 'SELECT * FROM ' . $args_get["table"] . ' WHERE '; 
    foreach($args_get as $arg_name => $arg):
        if($arg == '' || $arg_name =='table'):
            continue;
        endif;
        $type = sqlQuery("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = 'group25' AND table_name = '". $args_get["table"] . "' AND COLUMN_NAME ='" . $arg_name ."'",$db);
        if($type[0][0] == 'int'):
            $query = $query . $arg_name . "=" . $arg . ' ';
        elseif($type[0][0] == 'date'):
             $query = $query . $arg_name . " LIKE '" . $arg . "' ";
        else:
            $query = $query . $arg_name . " LIKE '%" . $arg . "%' ";
        endif;
            $query = $query . 'AND ';
    endforeach;
        $query = $query . '1';
        return sqlQuery($query,$db);
}
?>