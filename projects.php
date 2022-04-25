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
    <title>Database - Projets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <?php
      session_start();
      if(isset($_SESSION['user'])):
        require __DIR__ . '/functions.php';
        include("header.php");
        include("PDO.php")

        $projects_ordered = sqlQuery('SELECT P.NOM,P.DEPARTEMENT,P.CHEF,IF(P.DATE_FIN IS NOT NULL,"TERMINÉ",IF(P.BUDGET IS NULL,"EN ATTENTE","EN COURS DE ROUTE")) as STATUT,P.BUDGET,C.COUT,H.NB_HEURES,P.DATE_DEBUT,P.DATE_FIN,P.AVIS_EXPERT FROM Projet P,(SELECT P.NOM as NOM, SUM(T.NB_HEURES) as NB_HEURES FROM Tache T INNER JOIN Projet P ON P.NOM = T.PROJET GROUP BY P.NOM) H,(SELECT temp.P_C as PC,SUM(temp.COUT_C) as COUT FROM (SELECT Tache.PROJET as P_C, Tache.NB_HEURES*Fonction.TAUX_HORAIRE as COUT_C FROM Employe INNER JOIN Tache ON Tache.EMPLOYE = Employe.NO INNER JOIN Fonction ON Fonction.NOM = Employe.NOM_FONCTION) temp GROUP BY temp.P_C) C WHERE H.NOM = P.NOM AND C.PC = P.NOM ORDER BY DATE_DEBUT ASC, NOM ASC',$db);
        $columns = array(array("NOM"),array("DEPARTEMENT"),array("CHEF"),array("STATUT"),array("BUDGET"),array("COUT"),array("NB_HEURES"),array("DATE_DEBUT"),array("DATE_FIN"),array("AVIS_EXPERT"));
    ?>
        <div class="px-4 py-5 my-5">
          <div class="justify-content-center">
            <p>Tableau de bord des projets :</p>
            <?php printTable($projects_ordered,$columns); ?>
          </div>
        </div>
    <?php
      else:
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>