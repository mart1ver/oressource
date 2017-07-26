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
if (isset($_SESSION['id']) and $_SESSION['systeme'] = "oressource" and (strpos($_SESSION['niveau'], 'v'.$_GET['numero']) !== false)) {

  //on definit $adh en fonction $_POST['adh']
  if (isset($_POST['adh'])) {
    $adh = "oui";
  } else {
    $adh = "non";
  }

  if ($_SESSION['saisiec'] == "oui" and (strpos($_SESSION['niveau'], 'e') !== false)) {
    $antidate = $_POST['antidate'].date(" H:i:s");
    // Connexion à la base de données
    try {
      include('dbconfig.php');
    } catch (Exception $e) {
      die('Erreur : '.$e->getMessage());
    }
    // Insertion de la vente (sans les objets vendus) l'aide d'une requête préparée
    $req = $bdd->prepare('INSERT INTO ventes (timestamp, adherent, commentaire, id_point_vente, id_moyen_paiement, id_createur) VALUES(?,?, ?, ?, ?, ?)');
    $req->execute(array($antidate, $adh,  $_POST['comm'] , $_POST['id_point_vente'], 1, $_SESSION['id']));
    $id_vente = $bdd->lastInsertId();
    $req->closeCursor();
    //insertion des vendus dans la table vendus
    //$_POST['nlignes'] = le nombre de lignes a ajouter

    $i = 1;
    while ($i <= $_POST['nlignes']) {
      //on inserre les valeures pour chaque 'i' ($i = une ligne dans le ticket)
      // Insertion du post à l'aide d'une requête préparée

      $tid_type_objet = 'tid_type_objet'.$i;
      if (isset($_POST[$tid_type_objet])) {
        $tid_objet ='tid_objet'.$i;
        $tquantite = 'tquantite'.$i;
        $tprix = 'tprix'.$i;
        try {
          include('dbconfig.php');
        } catch (Exception $e) {
          die('Erreur : '.$e->getMessage());
        }
        $req = $bdd->prepare('INSERT INTO vendus (timestamp, id_vente,  id_type_dechet, id_objet, quantite, remboursement, id_createur) VALUES(?, ?,?, ?, ?, ?, ?)');
        $req->execute(array($antidate, $id_vente ,  $_POST[$tid_type_objet] ,  $_POST[$tid_objet] ,  $_POST[$tquantite], $_POST[$tprix], $_SESSION['id']));
        $req->closeCursor();
      }
      $i++;
    }
    // Redirection du visiteur vers la page de ventes
    header("Location:../ifaces/ventes.php?msg=Remboursement effectué &numero=".$_POST['id_point_vente']);
  } else {

    // Connexion à la base de données
    try {
      include('dbconfig.php');
    } catch (Exception $e) {
      die('Erreur : '.$e->getMessage());
    }
    // Insertion de la collecte (sans les pesées) l'aide d'une requête préparée
    $req = $bdd->prepare('INSERT INTO ventes (adherent, commentaire, id_point_vente,id_moyen_paiement, id_createur) VALUES(?,?, ?, ? ,?)');
    $req->execute(array($adh,  $_POST['comm'] , $_POST['id_point_vente'],1, $_SESSION['id']));
    $id_vente = $bdd->lastInsertId();
    $req->closeCursor();

    //insertion des vendus dans la table vendus
    //$_POST['nlignes'] = le nombre de lignes a ajouter

    $i = 1;
    while ($i <= $_POST['nlignes']) {
      //on inserre les valeures pour chaque 'i' ($i = une ligne dans le ticket)

      try {
        include('dbconfig.php');
      } catch (Exception $e) {
        die('Erreur : '.$e->getMessage());
      }
      // Insertion du post à l'aide d'une requête préparée
      $tid_type_objet = 'tid_type_objet'.$i;
      $tid_objet ='tid_objet'.$i;
      $tquantite = 'tquantite'.$i;
      $tprix = 'tprix'.$i;
      $req = $bdd->prepare('INSERT INTO vendus (id_vente,  id_type_dechet, id_objet, quantite, remboursement, id_createur) VALUES(?, ?, ?, ?, ?, ?)');
      $req->execute(array($id_vente ,  $_POST[$tid_type_objet] ,  $_POST[$tid_objet] ,  $_POST[$tquantite], $_POST[$tprix], $_SESSION['id']));
      $req->closeCursor();

      $i++;
    }
    // Redirection du visiteur vers la page de gestion des affectation
    header("Location:../ifaces/ventes.php?msg=Remboursement effectué &numero=".$_POST['id_point_vente']);
  }
} else {
  header('Location:../moteur/destroy.php');
}
