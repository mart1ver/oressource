<?php

require_once("../moteur/dbconfig.php");

// HACK fix me
function is_ajax() {
  /*
  return  isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
   */
  return true;
}

function output_values($reponse) {
  $categories = Array();
  $colors = Array();
  while ($data = $reponse->fetch()) {
    $categories[] = Array('label' => $data['nom'], 'value' => $data['somme']);
    $colors[] = $data['couleur'];
  } 
  return json_encode(Array(
    'data' => $categories,
    'colors' => $colors));
}

function query_type($bdd, $type, $num, $time_debut, $time_fin) {
  $fmt1 = array(
    'du' => $time_debut,
    'au' => $time_fin);
  $fmt2 = array(
    'du' => $time_debut,
    'au' => $time_fin,
    'numero' => $num);

  $reponse = null;
  if ($type == 'collect') {
    if ($num == 0) {
      $reponse = $bdd->prepare('
  SELECT type_collecte.couleur, type_collecte.nom, sum(pesees_collectes.masse) somme
  FROM type_collecte, pesees_collectes, collectes
  WHERE type_collecte.id = collectes.id_type_collecte
  AND pesees_collectes.id_collecte = collectes.id
  AND DATE(collectes.timestamp) BETWEEN :du AND :au GROUP BY nom');
  $reponse->execute($fmt1);
      } else {
  $reponse = $bdd->prepare('
  SELECT type_collecte.couleur, type_collecte.nom, sum(pesees_collectes.masse) somme 
  FROM type_collecte,pesees_collectes,collectes
   WHERE type_collecte.id = collectes.id_type_collecte
   AND pesees_collectes.timestamp BETWEEN :du AND :au AND pesees_collectes.id_collecte = collectes.id
   AND collectes.id_point_collecte = :numero
        GROUP BY nom');
      $reponse->execute(array(
    'du' => $time_debut,
    'au' => $time_fin,
    'numero' => $num));
    }
  } else if ($type == 'trash') {
    if ($num == 0) {
      $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
        FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
        GROUP BY nom');
      $reponse->execute($fmt1);
    } else {
      $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
        FROM type_dechets,pesees_collectes,collectes
        WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
        AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
        GROUP BY nom');
      $reponse->execute($fmt2);
    }
  } else if ($type == 'loca') {
    if ($num == 0) {
  $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(distinct pesees_collectes.masse) somme
    FROM type_dechets,pesees_collectes,collectes,localites WHERE localites.id = collectes.localisation AND pesees_collectes.id_collecte = collectes.id AND pesees_collectes.timestamp BETWEEN :du AND :au
    GROUP BY nom');
  $reponse->execute(array(
    'du' => $time_debut,
    'au' => $time_fin ));
    } else {
  $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(pesees_collectes.masse) somme
    FROM localites,pesees_collectes,collectes
    WHERE localites.id = collectes.localisation AND pesees_collectes.timestamp BETWEEN :du AND :au
    AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero GROUP BY nom');
  $reponse->execute(array(
    'du' => $time_debut,
    'au' => $time_fin,
    'numero' => $_GET['numero']));
    }
  }
  return $reponse;
}
