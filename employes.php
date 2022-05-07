<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8" />
    <title>Database - Employés</title>
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
          <div class="d-flex justify-content-center text-center">
            <form action='employes.php' method='POST'>
              <?php echo("Colonne de tri : ");?>
              <select name='column'>
                <option value='NO'>---</option>
                <option value='NO'>NO</option>
                <option value='NOM'>NOM</option>
                <option value='NB_PROJETS'>NB_PROJETS</option>
                <option value='NB_HEURES'>NB_HEURES</option>
              </select>
              <br><br>
              <?php echo("Sens de tri : ");?>
              <select name='direction'>
                <option value='ASC'>---</option>
                <option value='ASC'>Ascendant</option>
                <option value='DESC'>Descendant</option>
              </select>
              <br><br>
              <button type='submit'>Soumettre tri</button>
            </form>
            <br>
          </div>
          <p>Tableau de bord des employés :</p>
        <?php
          $tb_columns = array(array("NO"),array("NOM"),array("NB_PROJETS"),array("NB_HEURES"));
          // Génération du tableau pour la bonne colonne et le bon sens de tri
          if(isset($_POST['column']) AND isset($_POST['direction'])):
            $tb_employe = sqlQuery('SELECT temp.NO as NO, temp.NOM as NOM, COALESCE(temp.NB_PROJETS,0) as NB_PROJETS, COALESCE(temp.NB_HEURES,0) as NB_HEURES FROM (SELECT E.NO AS NO, E.NOM AS NOM, COUNT(T.PROJET) AS NB_PROJETS, SUM(T.NB_HEURES) AS NB_HEURES FROM Employe E LEFT JOIN Tache T ON E.NO = T.EMPLOYE GROUP BY E.NO ORDER BY ' . $_POST['column']  . ' '  . $_POST['direction'] . ')temp',$db);
          elseif(isset($_POST['column'])):
            $tb_employe = sqlQuery('SELECT temp.NO as NO, temp.NOM as NOM, COALESCE(temp.NB_PROJETS,0) as NB_PROJETS, COALESCE(temp.NB_HEURES,0) as NB_HEURES FROM (SELECT E.NO AS NO, E.NOM AS NOM, COUNT(T.PROJET) AS NB_PROJETS, SUM(T.NB_HEURES) AS NB_HEURES FROM Employe E LEFT JOIN Tache T ON E.NO = T.EMPLOYE GROUP BY E.NO ORDER BY ' . $_POST['column']  . ' ASC) temp',$db);
          else:
            $tb_employe = sqlQuery('SELECT temp.NO as NO, temp.NOM as NOM, COALESCE(temp.NB_PROJETS,0) as NB_PROJETS, COALESCE(temp.NB_HEURES,0) as NB_HEURES FROM (SELECT E.NO AS NO, E.NOM AS NOM, COUNT(T.PROJET) AS NB_PROJETS, SUM(T.NB_HEURES) AS NB_HEURES FROM Employe E LEFT JOIN Tache T ON E.NO = T.EMPLOYE GROUP BY E.NO ORDER BY E.NO ASC) temp',$db);
          endif;
          printTable($tb_employe,$tb_columns);
          
          // Création du data pour le tableau de bord des employés
          $data_employe = sqlQuery("SELECT 'NB_PROJETS' as 'DATA', SUM(temp1.NB_PROJETS) as 'SOMME', AVG(temp1.NB_PROJETS) as 'MOYENNE', VARIANCE(temp1.NB_PROJETS) as 'VARIANCE', MIN(temp1.NB_PROJETS) as 'MINIMUM', MAX(temp1.NB_PROJETS) as 'MAXIMUM' FROM (SELECT temp.NO as NO, temp.NOM as NOM, COALESCE(temp.NB_PROJETS,0) as NB_PROJETS, COALESCE(temp.NB_HEURES,0) as NB_HEURES FROM (SELECT E.NO AS NO, E.NOM AS NOM, COUNT(T.PROJET) AS NB_PROJETS, SUM(T.NB_HEURES) AS NB_HEURES FROM Employe E LEFT JOIN Tache T ON E.NO = T.EMPLOYE GROUP BY E.NO ORDER BY E.NO ASC) temp) temp1 UNION SELECT 'NB_HEURES' as 'DATA', SUM(temp2.NB_HEURES) as 'SOMME', AVG(temp2.NB_HEURES) as 'MOYENNE', VARIANCE(temp2.NB_HEURES) as 'VARIANCE', MIN(temp2.NB_HEURES) as 'MINIMUM', MAX(temp2.NB_HEURES) as 'MAXIMUM' FROM (SELECT temp.NO as NO, temp.NOM as NOM, COALESCE(temp.NB_PROJETS,0) as NB_PROJETS, COALESCE(temp.NB_HEURES,0) as NB_HEURES FROM (SELECT E.NO AS NO, E.NOM AS NOM, COUNT(T.PROJET) AS NB_PROJETS, SUM(T.NB_HEURES) AS NB_HEURES FROM Employe E LEFT JOIN Tache T ON E.NO = T.EMPLOYE GROUP BY E.NO ORDER BY E.NO ASC) temp) temp2",$db);
          $data_columns = array(array(" "),array("SOMME"),array("MOYENNE"),array("VARIANCE"),array("MINIMUM"),array("MAXIMUM"));
        ?>
        <br><br>
        <?php
          echo("Traitement des données des employés :");
          printTable($data_employe,$data_columns,$db);
          echo("<br> NB : La colonne SOMME indique le total des heures travaillées ou projets effectués par tous les employés alors que les 3 autres colonnes indiquent des données par rapport au nombre d'heures/projets par employé");
        ?>
        </div>
    <?php
      else:
        // si non connecté est renvoyé vers login.php
        header("Location:login.php");
      endif;
    ?>
  </body>
</html>