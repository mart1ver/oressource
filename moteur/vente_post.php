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

  if ($_POST['saisiec_user'] === 'oui' && (strpos($_POST['niveau_user'], 'e') !== false)) {
    $antidate = $_POST['antidate'] . date(' H:i:s');
    $req = $bdd->prepare('INSERT INTO ventes (timestamp, adherent, commentaire, id_point_vente, id_moyen_paiement, id_createur) VALUES(?,?, ?, ?, ?, ?)');
    $req->execute([$antidate, $adh, $_POST['comm'], $_POST['id_point_vente'], $_POST['moyen'], $_POST['id_user']]);
    $id_vente = $bdd->lastInsertId();
    $req->closeCursor();

    $i = 1;
    while ($i <= $_POST['nlignes']) {
      if (isset($_POST[$tid_type_objet])) {
        $tid_objet = 'tid_objet' . $i;
        $tquantite = 'tquantite' . $i;
        $tprix = 'tprix' . $i;
        $req = $bdd->prepare('INSERT INTO vendus (timestamp, id_vente,  id_type_dechet, id_objet, quantite, prix, id_createur) VALUES(?, ?,?, ?, ?, ?, ?)');
        $req->execute([$antidate, $id_vente, $_POST[$tid_type_objet], $_POST[$tid_objet], $_POST[$tquantite], $_POST[$tprix], $_POST['id_user']]);
        $id_vendu = $bdd->lastInsertId();
        $req->closeCursor();

        $tmasse = 'tmasse' . $i;
        if (isset($_POST[$tmasse])) {
          $req = $bdd->prepare('INSERT INTO pesees_vendus (timestamp,id_vendu,  masse,quantite, id_createur) VALUES(?,?,?,?,?)');
          $req->execute([$antidate, $id_vendu, $_POST[$tmasse], $_POST[$tquantite], $_POST['id_user']]);
          $req->closeCursor();
        }
      }
      $i++;
    }
    header('Location:../ifaces/ventes.php?numero=' . $_POST['id_point_vente']);
  } else {

    $req = $bdd->prepare('INSERT INTO ventes (adherent, commentaire, id_point_vente, id_moyen_paiement, id_createur) VALUES(?, ?, ?, ?, ?)');
    $req->execute([$adh, $_POST['comm'], $_POST['id_point_vente'], $_POST['moyen'], $_POST['id_user']]);
    $id_vente = $bdd->lastInsertId();
    $req->closeCursor();
    $i = 1;

    while ($i <= $_POST['nlignes']) {
      if (isset($_POST[$tid_type_objet])) {
        $tid_objet = 'tid_objet' . $i;
        $tquantite = 'tquantite' . $i;
        $tprix = 'tprix' . $i;
        $req = $bdd->prepare('INSERT INTO vendus (id_vente,  id_type_dechet, id_objet, quantite, prix, id_createur) VALUES(?,?, ?, ?, ?, ?)');
        $req->execute([$id_vente, $_POST[$tid_type_objet], $_POST[$tid_objet], $_POST[$tquantite], $_POST[$tprix], $_POST['id_user']]);
        $id_vendu = $bdd->lastInsertId();
        $req->closeCursor();
        $tmasse = 'tmasse' . $i;

        if (isset($_POST[$tmasse])) {
          $req = $bdd->prepare('INSERT INTO pesees_vendus (id_vendu,  masse,quantite, id_createur) VALUES(?,?,?,?)');
          $req->execute([$id_vendu, $_POST[$tmasse], $_POST[$tquantite], $_POST['id_user']]);
          $req->closeCursor();
        }
      }
      $i++;
    }
    header('Location:../ifaces/ventes.php?numero=' . $_POST['id_point_vente']);
  }
} else {
  header('Location:../moteur/destroy.php?motif=1');
}