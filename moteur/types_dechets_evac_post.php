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

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) and $_SESSION['systeme'] = "oressource" and (strpos($_SESSION['niveau'], 'k') !== false)) {
  try {
    // On se connecte à MySQL
    include('../moteur/dbconfig.php');
  } catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : '.$e->getMessage());
  }

  // Si tout va bien, on peut continuer
  $req = $bdd->prepare("SELECT SUM(id) FROM type_dechets_evac WHERE nom = :nom ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
  $req->execute(array('nom' => $_POST['nom']));
  $donnees = $req->fetch();
  $req->closeCursor(); // Termine le traitement de la requête

  if ($donnees['SUM(id)'] > 0) { // SI le titre existe
    header("Location:../ifaces/types_dechets_evac.php?err=Un type de dechet sortant porte deja le meme nom!&nom=".$_POST['nom']."&description=".$_POST['description']."&couleur=".substr($_POST['couleur'], 1));
    $req->closeCursor(); // Termine le traitement de la requête
  } else {
    $req->closeCursor(); // Termine le traitement de la requête

    //martin vert
    // Connexion à la base de données
    try {
      include('dbconfig.php');
    } catch (Exception $e) {
      die('Erreur : '.$e->getMessage());
    }

    // Insertion du post à l'aide d'une requête préparée
    // mot de passe crypté md5

    // Insertion du post à l'aide d'une requête préparée
    $req = $bdd->prepare('INSERT INTO type_dechets_evac (nom,  couleur, description, visible) VALUES(?, ?,  ?, ?)');
    $req->execute(array($_POST['nom'],  $_POST['couleur'] , $_POST['description'], "oui"));
    $req->closeCursor();

    // Redirection du visiteur vers la page de gestion des affectation
    header('Location:../ifaces/types_dechets_evac.php?msg=Type de dechet sortant enregistrée avec succes!');
  }
} else {
  header('Location:../moteur/destroy.php');
}
