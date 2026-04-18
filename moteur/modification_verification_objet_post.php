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

 function parms($string,$data) {
  $indexed=$data==array_values($data);
  foreach($data as $k=>$v) {
      if(is_string($v)) $v="'$v'";
      if($indexed) $string=preg_replace('/\?/',$v,$string,1);
      else $string=str_replace(":$k",$v,$string);
  }
  return $string;
}

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once '../moteur/dbconfig.php';
  $req = $bdd->prepare('UPDATE vendus SET
    prix = :prix,
    quantite = :quantite,
    id_last_hero = :id_last_hero
    WHERE id = :id');
  $req->execute(['prix' => $_POST['prix'], 'quantite' => $_POST['quantite'], 'id' => $_POST['id'], 'id_last_hero' => $_SESSION['id']]);
  $req->closeCursor();

  $req = $bdd->prepare('UPDATE ventes SET
    id_last_hero = :id_last_hero
    WHERE id = :id');
  $req->execute(['id' => $_POST['nvente'], 'id_last_hero' => $_SESSION['id']]);
  $req->closeCursor();


  

  if (isset($_POST['masse']) && $_POST['masse']>=0 ) {



    $count=0;
    $req = $bdd->prepare('SELECT COUNT(*) FROM pesees_vendus 
      WHERE id = :id');
    
    $req->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    $req->execute();

    $count = $req->fetchColumn();

    if ($count==1) { // Update
      $req = $bdd->prepare('UPDATE pesees_vendus SET
        id_last_hero = :id_last_hero,
        masse = :masse
        WHERE id = :id');
      $req->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
      $req->bindParam(':id_last_hero', $_SESSION['id'], PDO::PARAM_INT);
      $req->bindParam(':masse', $_POST['masse'], PDO::PARAM_STR);
      $req->execute();
      $req->closeCursor();
    }
    else { // Insert

      $sql = 'INSERT INTO pesees_vendus (
        timestamp,
        last_hero_timestamp,
        id,
        masse,
        quantite,
        id_createur,
        id_last_hero
      ) VALUES (
        :timestamp,
        :timestamp1,
        :id_vendu,
        :masse,
        :quantite,
        :id_createur,
        :id_createur1)';
    $req = $bdd->prepare($sql);
    $date=new DateTime('now');
    $req->bindValue(':timestamp', $date->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $req->bindValue(':timestamp1', $date->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $req->bindValue(':id_createur', $_SESSION['id'], PDO::PARAM_INT);
    $req->bindValue(':id_createur1', $_SESSION['id'], PDO::PARAM_INT);
    $masse = $_POST['masse'];
    $quantite = $_POST['quantite'];
    $id_vendu = $_POST['id'];
    if ($masse >= 0.000 && $quantite > 0) {
        $req->bindValue(':masse', $masse);
        $req->bindValue(':id_vendu', $id_vendu, PDO::PARAM_INT);
        $req->bindValue(':quantite', $quantite, PDO::PARAM_INT);
        $req->execute();
    } elseif ($masse < 0.000 && $quantite === 0) {
        $req->closeCursor();
        throw new UnexpectedValueException('masse < 0.00 ou type item inconnu');
    }
    $req->closeCursor();
    
    }


  }
  header('Location:../ifaces/verif_vente.php?numero=' . $_POST['npoint'] . '&date1=' . $_POST['date1'] . '&date2=' . $_POST['date2']);
} else {
  header('Location:../moteur/destroy.php');
}
