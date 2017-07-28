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

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'l') !== false)) {
  require_once '../moteur/dbconfig.php';
  // si l'addresse mail est deja utillisée
  $req = $bdd->prepare('SELECT SUM(id) FROM utilisateurs WHERE mail = :amail ');
  $req->execute(['amail' => $_POST['mail']]);
  $donnees = $req->fetch();

  $req->closeCursor();
  if ($donnees['SUM(id)'] > 0) {
    header('Location:../ifaces/utilisateurs.php?err=Cette addresse email est deja utilisée par un autre utilisateur!&nom=' . $_POST['nom'] . '&prenom=' . $_POST['prenom'] . '&mail=' . $_POST['mail']);
    die();
  }

  if ($_POST['pass1'] = $_POST['pass2']) {
    //recuperation des autorisations simples (adh,bilans,g,h,l,j,k,mailing,prets) concatenés dans la variable $niveau
    $niveau = $_POST['niveaua'] . $_POST['niveaubi'] . $_POST['niveaug'] . $_POST['niveauh'] . $_POST['niveaul'] . $_POST['niveauj'] . $_POST['niveauk'] . $_POST['niveaum'] . $_POST['niveaup'] . $_POST['niveaue'];
    //recuperation des eventuelles autorisations liées au points de collectes à concatener avec la variable $niveau (c1,c2,c3...)
    $niveaucollecte = '';
    $reponsec = $bdd->query('SELECT id FROM points_collecte');

    while ($donneesc = $reponsec->fetch()) {
      if (isset($_POST['niveauc' . $donneesc['id']])) {
        $niveaucollecte = $niveaucollecte . 'c' . $donneesc['id'];
      }
    }
    $reponsec->closeCursor();
    //recuperation des eventuelles autorisations liées au points de vente à concatener avec la variable $niveau (v1,v2,v3...)
    $niveauvente = '';
    $reponsev = $bdd->query('SELECT id FROM points_vente');

    while ($donneesv = $reponsev->fetch()) {
      if (isset($_POST['niveauv' . $donneesv['id']])) {
        $niveauvente = $niveauvente . 'v' . $donneesv['id'];
      }
    }
    $reponsev->closeCursor();
    //recuperation des eventuelles autorisations liées au points de sortie hors boutique à concatener avec la variable $niveau (s1,s2,s3...)
    $niveausortie = '';
    $reponses = $bdd->query('SELECT id FROM points_sortie');

    while ($donneess = $reponses->fetch()) {
      if (isset($_POST['niveaus' . $donneess['id']])) {
        $niveausortie = $niveausortie . 's' . $donneess['id'];
      }
    }
    $reponses->closeCursor();
    $req = $bdd->prepare('INSERT INTO utilisateurs (nom, prenom, mail, pass, niveau) VALUES( ?, ?, ?, ?, ? )');
    $req->execute([$_POST['nom'], $_POST['prenom'], $_POST['mail'], md5($_POST['pass1']), $niveaucollecte . $niveauvente . $niveausortie . $niveau]);
    $req->closeCursor();

    header('Location: ../ifaces/utilisateurs.php?msg=Utilisateur ajouté avec succes!');
  } else {
    header('Location: ../ifaces/utilisateurs.php?err=Veuillez confirmer votre mot de passe&nom=' . $_POST['nom'] . '&prenom=' . $_POST['prenom'] . '&mail=' . $_POST['mail']);
  }
} else {
  header('Location:../moteur/destroy.php');
}
