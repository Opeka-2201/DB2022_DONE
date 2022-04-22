<?php

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


function sqlQuery($query, $db){
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

//SELECT * FROM Projet WHERE NOM LIKE '%MED%'
//SELECT * FROM Projet WHERE CHEF=8105
//SELECT * FROM Projet WHERE CHEF=8105 AND NOM LIKE '%MED%'

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

function cmpTable($t1,$t2){
  //checks if all values of t1 are in t2
  foreach($t1 as $v1):
    $val1 = $v1[0];
    $isValOk = false;
    foreach($t2 as $v2):
      $val2 = $v2[0];
      if($val1 == $val2):
        $isValOk = true;
        break;
      else:
        continue;
      endif;
    endforeach;

    if(!$isValOk):
      return false;
    endif;
  endforeach;
  return true;
}
?>