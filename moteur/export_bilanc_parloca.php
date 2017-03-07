<?php
session_start();

function output_csv_request(PDOStatement $stmt, $begin, $end, $filename, $labels) {
  $stmt->bindParam(':du', $begin, PDO::PARAM_STR);
  $stmt->bindParam(':au', $end, PDO::PARAM_STR);
  $stmt->execute();
  // On affiche chaque entree une à une
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

  header("Content-type: text/x-csv");
  header("Content-disposition: attachment; filename=$filename" . date("Ymd").".csv");
  header("Cache-Control: no-cache, no-store, must-revalidate");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

  $output = fopen("php://output", "w");
  fputcsv($output, $labels);
  foreach ($data as $row) {
    fputcsv($output, $row);
  }
  fclose($output);
}

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id'])
  && $_SESSION['systeme'] = "oressource"
  && (strpos($_SESSION['niveau'], 'bi') !== false)) {
  //on convertit les deux dates en un format compatible avec la bdd

  $txt1  = $_GET['date1'];
  $date1ft = DateTime::createFromFormat('d-m-Y', $txt1);
  $time_debut = $date1ft->format('Y-m-d') . " 00:00:00";

  $txt2  = $_GET['date2'];
  $date2ft = DateTime::createFromFormat('d-m-Y', $txt2);
  $time_fin = $date2ft->format('Y-m-d') . " 23:59:59";
  include('../moteur/dbconfig.php');

  if ($_GET['numero'] == 0) {
    // on determine les masses totales collèctés sur cete periode(pour tout les points)
    $reponse = $bdd->prepare('
SELECT
  localites.nom,
  SUM(pesees_collectes.masse) sum,
  pesees_collectes.timestamp,
  COUNT(distinct collectes.id) nbcol
FROM
  pesees_collectes,
  collectes,
  localites
WHERE
  (pesees_collectes.timestamp BETWEEN :du AND :au)
  AND localites.id = collectes.localisation
  AND pesees_collectes.id_collecte = collectes.id
GROUP BY
  localites.nom
');
  output_csv_request($reponse, $time_debut, $time_fin, 'bilan_collecte_localite_tout_points_',
  array('localités','masse','date','nombre de collectes'));
  } else if ($_GET['numero'] >= 1
    && $_GET['numero']  <= 5) {
    // FIXME: need to be replaced by max(pesees_collectes.id_collecte
    $reponse = $bdd->prepare('
SELECT
  localites.nom,
  SUM(pesees_collectes.masse) sum,
  pesees_collectes.timestamp,
  COUNT(distinct collectes.id) nbcol
FROM
  pesees_collectes,
  collectes,
  localites
WHERE
  (pesees_collectes.timestamp BETWEEN :du AND :au)
  AND localites.id = collectes.localisation
  AND pesees_collectes.id_collecte = collectes.id
  AND collectes.id_point_collecte = :numero
GROUP BY
  localites.nom
');
 $reponse->bindValue(':numero', $_GET['numero']);
  output_csv_request($reponse, $time_debut, $time_fin, 'bilan_collecte_localite_point_'.$_GET['numero'] .'_',
    array('localités','masse','date','nombre de collectes'));
    }
  $reponse->closeCursor();
} else {
    header('Location:../moteur/destroy.php');
}
?>

