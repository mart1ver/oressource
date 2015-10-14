<?php
header("content-type:application/json; charset=utf-8");

require_once("../backend/ajax_tools.php");


if (is_ajax()
  && isset($_GET['type'])
  && isset($_GET['numero'])
  && (isset($_GET['date1']) || isset($_GET['date2']))) {
  $num = $_GET['numero'];
  $type = $_GET['type'];

  $txt1  = $_GET['date1'];
  $date1ft = DateTime::createFromFormat('d-m-Y', $txt1);
  $time_debut = $date1ft->format('Y-m-d') . " 00:00:00";

  $txt2  = $_GET['date2'];
  $date2ft = DateTime::createFromFormat('d-m-Y', $txt2);
  $time_fin = $date2ft->format('Y-m-d')  . " 23:59:59";

  $reponse = query_type($bdd, $type, $num, $time_debut, $time_fin);
  echo(output_values($reponse));
  $reponse->closeCursor();
} else {
  var_dump("error");
}
exit();
