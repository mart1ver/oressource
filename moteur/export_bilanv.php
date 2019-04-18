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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'bi') !== false)) {
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

  // on affiche la periode visée
  if ($date1 === $date2) {
    $nomfic = "bilan_vente_$date1.csv";
    $xls_output = "Le $date1";
  } else {
    $nomfic = "bilan_vente_${date1}_au_$date2.csv";
    $xls_output = "Du $date1 au $date2";
  }

  //  if ($numero == 0) {
  $xls_output .= "\nPour tous les points de vente\n\n";
  //Ligne des noms des champs
  $xls_output .= "Réf\tRéf moyen de paiement\tDate\tAdhérent ?\tCommentaire\tRéf point de vente\tPoint de vente\tRéf vendeur\tNbx d'obj\tTotal quantités\tTotal prix\tTotal remboursement\n";
  //  }
  $req = $bdd->prepare('SELECT ventes.id, id_moyen_paiement, ventes.timestamp, adherent, ventes.commentaire, id_point_vente, nom, ventes.id_createur, count(vendus.id), sum(quantite), sum(prix*quantite),sum(remboursement)
    FROM ventes, vendus,points_vente WHERE DATE(ventes.timestamp) BETWEEN :du AND :au AND id_vente=ventes.id AND id_point_vente=points_vente.id GROUP BY ventes.id');
  $req->execute([':du' => $time_debut, ':au' => $time_fin]);
  while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
    $xls_output .= implode("\t", array_slice($donnees, 0, -2));
    $xls_output .= str_replace('.', ',', $donnees['sum(prix*quantite)']) . "\t";
    $xls_output .= str_replace('.', ',', $donnees['sum(remboursement)']) . "\n";
  }
  $req->closeCursor();

  header('Content-type: text/csv');
  header('Content-disposition: attachment; filename=' . $nomfic);
  echo $xls_output;
} else {
  header('Location:../moteur/destroy.php');
}
