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

require_once('../core/session.php');

session_start();

// Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && is_allowed_config()) {
  include_once('../moteur/dbconfig.php');

  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
  $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
  $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);
  $surface = filter_input(INPUT_POST, 'surface', FILTER_VALIDATE_INT);
  $commentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING);
  // La regex capture SEULEMENT les couleurs en HEXA.
  $couleur = filter_input(INPUT_POST, 'couleur', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/(^#[0-9A-Fa-f]{6})/']]);

  // TODO: rejeter si une des variables nulle.

  $req = $bdd->prepare('SELECT
    id
    FROM points_vente
    WHERE
    id = :id
    LIMIT 1');
  $req->bindValue(':id', (int) $id, PDO::PARAM_INT);
  $donnees = $req->fetch(PDO::FETCH_ASSOC);

  // Si il existe pas
  if (isset($donnees['id'])) {
    $base = 'Location:../ifaces/edition_points_vente.php?';
    $error = 'err=Aucun point de vente porte deja le meme nom mais vous pouvez le creer!';
    header($base . $error . '&nom=' . $nom . '&adresse=' . $adresse . '&surface='
      . $surface . '&commentaire=' . $commentaire . '&couleur=' . substr($couleur, 1));
  } else {
    $req = $bdd->prepare('UPDATE points_vente
      SET nom = :nom,
      adresse = :adresse ,
      commentaire = :commentaire,
      surface_vente = :surface_vente,
      couleur = :couleur
      WHERE id = :id');
    $req->bindvalue(':adresse', $adresse, PDO::PARAM_STR);
    $req->bindvalue(':commentaire', $commentaire, PDO::PARAM_STR);
    $req->bindvalue(':surface_vente', $surface, PDO::PARAM_INT);
    $req->bindvalue(':couleur', $couleur, PDO::PARAM_STR);
    $req->bindvalue(':nom', $nom, PDO::PARAM_STR);
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
    header('Location:../ifaces/edition_points_vente.php?msg=' . $nom . ' bien mis a jour');
  }
} else {
  header('Location:../moteur/destroy.php');
}
