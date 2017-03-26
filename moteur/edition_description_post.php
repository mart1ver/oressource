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

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && ( strpos($_SESSION['niveau'], 'k') !== false)) {

  require_once('dbconfig.php');

  $mail = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
  $atva = $_POST['atva'] === 'oui' ? 'oui' : 'non';
  $lot = $_POST['lot'] === 'oui' ? 'oui' : 'non';
  $viz = $_POST['viz'] === 'oui' ? 'oui' : 'non';
  $saisiec = $_POST['saisiec'] === 'oui' ? 'oui' : 'non';
  $affsp = $_POST['affsp'] === 'oui' ? 'oui' : 'non';
  $affss = $_POST['affss'] === 'oui' ? 'oui' : 'non';
  $affsr = $_POST['affsr'] === 'oui' ? 'oui' : 'non';
  $affsd = $_POST['affsd'] === 'oui' ? 'oui' : 'non';
  $affsde = $_POST['affsde'] === 'oui' ? 'oui' : 'non';
  $pes_vente = $_POST['pes_vente'] === 'oui' ? 'oui' : 'non';
  $force_pes_vente = $_POST['force_pes_vente'] === 'oui' ? 'oui' : 'non';

  $req = $bdd->prepare('
    UPDATE description_structure
    SET id = :id,
    nom = :nom,
    adresse = :adresse,
    description = :description,
    siret = :siret,
    telephone = :telephone,
    mail =:mail,
    taux_tva = :taux_tva,
    tva_active= :tva_active,
    cr = :cr,
    lot = :lot,
    viz = :viz,
    nb_viz = :nb_viz,
    saisiec =: saisiec,
    affsp = :affsp,
    affss = :affss,
    affsr = :affsr,
    affsd = :affsd,
    affsde = :affsde,
    pes_vente = :pes_vente,
    force_pes_vente = :force_pes_vente
    WHERE id = :id');

  $req->execute([
      'id' => 1,
      'nom' => $_POST['nom'],
      'description' => $_POST['description'],
      'siret' => $_POST['siret'],
      'mail' => $mail,
      'adresse' => $_POST['adresse'],
      'telephone' => $_POST['telephone'],
      'id_localite' => $_POST['localite'],
      'taux_tva' => $_POST['ttva'],
      'tva_active' => $atva,
      'lot' => $lot,
      'viz' => $viz,
      'saisiec' => $saisiec,
      'nb_viz' => $_POST['nb_viz'],
      'cr' => $_POST['cr'],
      'affsp' => $affsp,
      'affss' => $affss,
      'affsr' => $affsr,
      'affsd' => $affsd,
      'affsde' => $affsde,
      'pes_vente' => $pes_vente,
      'force_pes_vente' => $force_pes_vente
  ]);

  $req->closeCursor();
  header('Location:../ifaces/edition_description.php?msg=Configuration sauvegardée.');
} else {
  header('Location:../moteur/destroy.php');
}
