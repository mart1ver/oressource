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

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/validation.php';

session_start();

// TODO: Réecrire la gestion des remboursements pour avoir quelque chose de plus proche de ventes.
// Pourquoi pas sinon fair une table dédiée.

if (is_valid_session() && is_allowed_vente_id($_POST['id_point_vente'])) {
  require_once '../moteur/dbconfig.php';

  $antidate = is_allowed_edit_date() && is_allowed_saisie_date() ? parseDate($_POST['antidate']) : new DateTime('now');
  $antidate = $antidate->format('Y-m-d H:i:s');

  $req = $bdd->prepare('INSERT INTO ventes (timestamp, last_hero_timestamp, commentaire, id_point_vente, id_moyen_paiement, id_createur, id_last_hero) VALUES (?, ?, ?, ?, ?, ?, ?)');
  $req->execute([$antidate, $antidate, $_POST['comm'], $_POST['id_point_vente'], 1, $_SESSION['id'], $_SESSION['id']]);
  $id_vente = $bdd->lastInsertId();
  $req->closeCursor();

  $i = 1;
  $req = $bdd->prepare('INSERT INTO vendus (
    timestamp,
    last_hero_timestamp,
    id_vente,
    id_type_dechet,
    id_objet,
    quantite,
    prix,
    remboursement,
    id_createur,
    id_last_hero
  ) VALUES (
    :timestamp,
    :timestamp1,
    :id_vente,
    :id_type_dechet,
    :id_objet,
    :quantite,
    0,
    :remboursement,
    :id_createur,
    :id_createur1)');
  $req->bindValue(':timestamp', $antidate, PDO::PARAM_STR);
  $req->bindValue(':timestamp1', $antidate, PDO::PARAM_STR);
  $req->bindValue(':id_vente', $id_vente, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $_SESSION['id'], PDO::PARAM_INT);
  $req->bindValue(':id_createur1', $_SESSION['id'], PDO::PARAM_INT);

  while ($i <= $_POST['nlignes']) {
    $tid_type_objet = 'tid_type_objet' . $i;
    $tid_objet = 'tid_objet' . $i;
    $tquantite = 'tquantite' . $i;
    $tprix = 'tprix' . $i;

    if (!$_POST[$tid_type_objet]) {
      header("Location:../ifaces/ventes.php?err=Les remboursement de sans type d'objet ou dechet ne sont pas valides&numero=" . $_POST['id_point_vente']);
      die();
    }

    if (($_POST[$tprix] <= 0.0)) {
      header('Location:../ifaces/ventes.php?err=Les remboursement de 0 euros ne sont pas valides&numero=' . $_POST['id_point_vente']);
      die();
    }

    if (($_POST[$tquantite] <= 0)) {
      header('Location:../ifaces/ventes.php?err=Les remboursement avec une quantité nulle ne sont pas possibles&numero=' . $_POST['id_point_vente']);
      die();
    }

    $req->bindValue(':id_type_dechet', $_POST[$tid_type_objet], PDO::PARAM_INT);
    $req->bindValue(':id_objet', $_POST[$tid_objet], PDO::PARAM_INT);
    $req->bindValue(':quantite', $_POST[$tquantite], PDO::PARAM_INT);
    $req->bindValue(':remboursement', $_POST[$tprix], PDO::PARAM_STR);
    $req->execute();

    $i++;
  }
  $req->closeCursor();
  header('Location:../ifaces/ventes.php?msg=Remboursement effectué &numero=' . $_POST['id_point_vente']);
} else {
  header('Location:../moteur/destroy.php');
}
