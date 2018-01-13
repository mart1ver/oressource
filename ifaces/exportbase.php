<?php

session_start();
require_once('../core/session.php');
require_once('../core/requetes.php');
require_once('../core/composants.php');
if (is_valid_session() && is_allowed_config()) {
  require_once('../moteur/dbconfig.php');
  $struct = structure($bdd);
  $mysqlExportPath = '../mysql/';
  $mysqlExportFileName = 'sauvegarde_oressource';
  $mysqlExportFileExtention = '.sql';
  $mysqlExportPathServer = $mysqlExportPath . $mysqlExportFileName . $mysqlExportFileExtention;
  $mysqlExportFile = $mysqlExportPath . $mysqlExportFileName . $struct['nom'] . date("dmY") . $mysqlExportFileExtention;
  $command = 'mysqldump --opt -h' . $host . ' -u' . $user . ' -p' . $pass . ' ' . $base . ' > ' . $mysqlExportPathServer;
  $output = array();
  exec($command, $output, $worked);
  switch ($worked) {
    case 0:
      header('Content-Type: text/html; charset=utf-8');
      header("Content-Transfer-Encoding: Binary");
      header("Content-disposition: attachment; filename=\"" . basename($mysqlExportFile) . "\"");
      readfile($mysqlExportPathServer); // do the double-download-dance (dirty but worky)
      header('Location:structures.php');
      break;
    case 1:
      header("Location:structures.php?err=Probleme pendant l'export du fichier ");
      break;
    case 2:
      header("Location:structures.php?err=Probleme pendant l'export de la base ");
      break;
  }
} else {
  header('Location:../moteur/destroy.php');
  die();
}
?>