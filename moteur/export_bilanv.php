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

if (!(isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'bi') !== false))) {
  header('Location:../moteur/destroy.php');
  exit();
}

  require_once '../moteur/dbconfig.php';

  $numero = htmlspecialchars($_GET['numero']);
  //on convertit les deux dates en un format compatible avec la bdd
  $date1 = $_GET['date1'];
  $date1ft = DateTime::createFromFormat('d-m-Y', $date1);
  $time_debut = $date1ft->format('Y-m-d');
  $time_debut .= ' 00:00:00';

  $date2 = $_GET['date2'];
  $date2ft = DateTime::createFromFormat('d-m-Y', $date2);
  $time_fin = $date2ft->format('Y-m-d');
  $time_fin .= ' 23:59:59';

  $nomfic = 'oressource_bilanventes';
  if ($numero == 0) {
    $nomfic .= '_global_';
  }else{
    $nomfic .= '_pointdevente-'.$numero.'_';
  }

  // on affiche la periode visée
  if ($date1 === $date2) {
    $nomfic .= "$date1.csv";
  } else {
    $nomfic .= "${date1}_au_$date2.csv";
  }
  //Ligne des noms des champs
  $xls_output = "Réf;Réf moyen de paiement;Moyen de paiement;Date;Commentaire;Réf point de vente;Point de vente;Réf vendeur;Nbx d'obj;Total quantités;Total prix;Total remboursement\n";
  //  }
  $req = $bdd->prepare('SELECT ventes.id, id_moyen_paiement, moyens_paiement.nom as moyen_paiement, ventes.timestamp, ventes.commentaire, id_point_vente, points_vente.nom, ventes.id_createur, count(vendus.id), sum(quantite), sum(prix*quantite),sum(remboursement)
    FROM ventes INNER JOIN vendus ON id_vente=ventes.id INNER JOIN points_vente ON id_point_vente = points_vente.id INNER JOIN moyens_paiement ON moyens_paiement.id = ventes.id_moyen_paiement WHERE DATE(ventes.timestamp) BETWEEN :du AND :au GROUP BY ventes.id');
  $req->execute([':du' => $time_debut, ':au' => $time_fin]);
  while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
    $donnees = array_map(function($k) {
          if (is_numeric($k)) {
            $k = str_replace('.', ',', $k);
          }
          $k = str_replace(';', ',', $k);
          $k = str_replace('"', ' ', $k);
          if (strpos($k, "'") !== false) {
            $k = '"'.$k.'"';
          }
          return $k;
        }
      , $donnees);
    $xls_output .= implode(";", array_slice($donnees, 0, -2));
    $xls_output .= ';';
    $xls_output .= str_replace('.', ',', $donnees['sum(prix*quantite)']) . ";";
    $xls_output .= str_replace('.', ',', $donnees['sum(remboursement)']) . "\n";
  }
  $req->closeCursor();

  header('Content-type: text/csv');
  header('Content-disposition: attachment; filename=' . $nomfic);
  echo $xls_output;
