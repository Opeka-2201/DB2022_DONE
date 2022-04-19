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
?>