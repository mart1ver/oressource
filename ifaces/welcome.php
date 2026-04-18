<?php

require_once('../core/session.php');

if(!is_valid_session()) {
  header('Location:../moteur/destroy.php');
  exit();
}

if (is_allowed_vente()) {
  if (preg_match('/v([0-9]+)/', $_SESSION['niveau'], $matches))  {
    for ($i = 1 ; $i < count($matches) ; $i++) {
      if (is_allowed_vente_id($matches[$i])) {
        header('Location: ventes.php?numero='.$matches[$i]);
        exit();
      }
    }
  }
}

if (is_allowed_collecte()) {
  if (preg_match('/c([0-9]+)/', $_SESSION['niveau'], $matches))  {
    for ($i = 1 ; $i < count($matches) ; $i++) {
      if (is_allowed_collecte_id($matches[$i])) {
        header('Location: collecte.php?numero='.$matches[$i]);
        exit();
      }
    }
  }
}

if (is_allowed_sortie()) {
  if (preg_match('/s([0-9]+)/', $_SESSION['niveau'], $matches))  {
    for ($i = 1 ; $i < count($matches) ; $i++) {
      if (is_allowed_sortie_id($matches[$i])) {
        header('Location: sortiesp.php?numero='.$matches[$i]);
        exit();
      }
    }
  }
}

if (is_allowed_bilan()) {
  header('Location: recapitulatif.php');
  exit();
}

header('Location:../moteur/destroy.php');
exit();
