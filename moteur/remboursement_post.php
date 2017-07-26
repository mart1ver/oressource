<?php

/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'v' . $_GET['numero']) !== false)) {
  require_once '../moteur/dbconfig.php';

  $adh = isset($_POST['adh']) ? 'oui' : 'non';

  if ($_SESSION['saisiec'] === 'oui' && (strpos($_SESSION['niveau'], 'e') !== false)) {
    $antidate = $_POST['antidate'] . date(' H:i:s');
    $req = $bdd->prepare('INSERT INTO ventes (timestamp, adherent, commentaire, id_point_vente, id_moyen_paiement, id_createur) VALUES(?,?, ?, ?, ?, ?)');
    $req->execute([$antidate, $adh, $_POST['comm'], $_POST['id_point_vente'], 1, $_SESSION['id']]);
    $id_vente = $bdd->lastInsertId();
    $req->closeCursor();

    $i = 1;
    while ($i <= $_POST['nlignes']) {
      $tid_type_objet = 'tid_type_objet' . $i;
      if (isset($_POST[$tid_type_objet])) {
        $tid_objet = 'tid_objet' . $i;
        $tquantite = 'tquantite' . $i;
        $tprix = 'tprix' . $i;
        $req = $bdd->prepare('INSERT INTO vendus (timestamp, id_vente,  id_type_dechet, id_objet, quantite, remboursement, id_createur) VALUES(?, ?,?, ?, ?, ?, ?)');
        $req->execute([$antidate, $id_vente, $_POST[$tid_type_objet], $_POST[$tid_objet], $_POST[$tquantite], $_POST[$tprix], $_SESSION['id']]);
        $req->closeCursor();
      }
      $i++;
    }

    header('Location:../ifaces/ventes.php?msg=Remboursement effectué &numero=' . $_POST['id_point_vente']);
  } else {
    $req = $bdd->prepare('INSERT INTO ventes (adherent, commentaire, id_point_vente,id_moyen_paiement, id_createur) VALUES(?,?, ?, ? ,?)');
    $req->execute([$adh, $_POST['comm'], $_POST['id_point_vente'], 1, $_SESSION['id']]);
    $id_vente = $bdd->lastInsertId();
    $req->closeCursor();

    $i = 1;
    while ($i <= $_POST['nlignes']) {
      $tid_type_objet = 'tid_type_objet' . $i;
      $tid_objet = 'tid_objet' . $i;
      $tquantite = 'tquantite' . $i;
      $tprix = 'tprix' . $i;
      $req = $bdd->prepare('INSERT INTO vendus (id_vente,  id_type_dechet, id_objet, quantite, remboursement, id_createur) VALUES(?, ?, ?, ?, ?, ?)');
      $req->execute([$id_vente, $_POST[$tid_type_objet], $_POST[$tid_objet], $_POST[$tquantite], $_POST[$tprix], $_SESSION['id']]);
      $req->closeCursor();
      $i++;
    }
    header('Location:../ifaces/ventes.php?msg=Remboursement effectué &numero=' . $_POST['id_point_vente']);
  }
} else {
  header('Location:../moteur/destroy.php');
}
