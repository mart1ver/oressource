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


//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && is_allowed_config()) {
    include_once('../moteur/dbconfig.php');

  $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
  $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);
  $surface = filter_input(INPUT_POST, 'surface', FILTER_VALIDATE_INT);
  $commentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING);
  // La regex capture SEULEMENT les couleurs en HEXA.
  $couleur = filter_input(INPUT_POST, 'couleur', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' =>'/(^#[0-9A-Fa-f]{6})/']]);
  $req = $bdd->prepare('SELECT id
                        FROM points_vente
                        WHERE nom = :nom');
  $req->bindValue(':nom', $nom, PDO::PARAM_STR);
  $req->execute();
  $donnees = $req->fetch(PDO::FETCH_ASSOC);

  // Si le nom est utilise
  if ($donnees['SUM(id)'] > 0) {
    $base = 'Location:../ifaces/edition_points_vente.php?';
    $error = 'err=Un point de vente porte deja le meme nom!';
    header($base . $error . '&nom=' . $nom . '&adresse=' . $adresse . '&surface='
          . $surface . '&commentaire=' . $commentaire . '&couleur=' . substr($couleur, 1));
    header($base . $error . '&nom=' . $nom
      . "&adresse=" . $adresse . '&surface=' . $surface
      . '&commentaire=' . $commentaire . '&couleur=' . substr($couleur, 1));
  } else {
    $req = $bdd->prepare('INSERT INTO points_vente
                          (nom, adresse, couleur,
                          commentaire, surface_vente, visible)
                          VALUES(:nom, :adresse, :couleur, :commentaire, :surface_vente, :visible)');
    $req->bindvalue(':adresse', $adresse, PDO::PARAM_STR);
    $req->bindvalue(':commentaire', $commentaire, PDO::PARAM_STR);
    $req->bindvalue(':surface_vente', $surface, PDO::PARAM_INT);
    $req->bindvalue(':couleur', $couleur, PDO::PARAM_STR);
    $req->bindvalue(':nom', $nom, PDO::PARAM_STR);
    $req->bindvalue(':visible', 'oui', PDO::PARAM_STR);
    $req->execute();
    header('Location:../ifaces/edition_points_vente.php?msg=Point de vente cree avec succes!');
  }
} else {
  header('Location:../moteur/destroy.php');
}
