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
require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
   if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>Modifier le remboursement n° <?= $_GET['nvente']?></h1>
 <div class="panel-body">




<br>





</div>
<h1>Objets inclus dans ce remboursement</h1>
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>Type d'objet:</th>
            <th>Objet:</th>
            <th>Quantité</th>
            <th>Prix</th>
            <th>Auteur de la ligne</th>
            <th></th>
            <th>Modifié par</th>
            <th>Le:</th>


          </tr>
        </thead>
        <tbody>


        <?php

            // Si tout va bien, on peut continuer
/*
'SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
FROM type_dechets,pesees_collectes
WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
GROUP BY nom'

SELECT pesees_collectes.id ,pesees_collectes.timestamp  ,type_dechets.nom  , pesees_collectes.masse
                       FROM pesees_collectes ,type_dechets
                       WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.id_collecte = :id_collecte
*/



            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');

$req = $bdd->prepare('SELECT vendus.id ,vendus.timestamp
 ,type_dechets.nom type
,grille_objets.nom objet
 ,vendus.remboursement
 ,vendus.quantite
,utilisateurs.mail

 FROM vendus, type_dechets, grille_objets ,utilisateurs

WHERE vendus.id_vente = :id_vente

AND grille_objets.id = vendus.id_objet
AND type_dechets.id = vendus.id_type_dechet
AND utilisateurs.id = vendus.id_createur');
$req->execute(array('id_vente' => $_GET['nvente']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr>
            <td><?= $donnees['id']?></td>
            <td><?= $donnees['timestamp']?></td>
            <td><?= $donnees['type']?></td>
            <td><?= $donnees['objet']?></td>



<td><?= $donnees['quantite']?></td>
<td><?= $donnees['remboursement']?></td>
<td><?= $donnees['mail']?></td>

<td><form action="modification_verification_objet_remboursement.php" method="post">
<input type="hidden" name ="id" id="id" value="<?= $donnees['id']?>">
<input type="hidden" name ="nvente" id="nvente" value="<?= $_GET['nvente']?>">
<input type="hidden" name ="quantite" id="quantite" value="<?= $donnees['quantite']?>">
<input type="hidden" name ="remboursement" id="remboursement" value="<?= $donnees['remboursement']?>">
<input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']?>">

  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>
</td>




<td><?php
$req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, vendus
                       WHERE  vendus.id = :id_vendu
                       AND utilisateurs.id = vendus.id_last_hero');
$req3->execute(array('id_vendu' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           { ?>



<?= $donnees3['mail']?>


         <?php }
            $req3->closeCursor(); // Termine le traitement de la requête 3
                ?></td>


<td><?php
$req3 = $bdd->prepare('SELECT vendus.last_hero_timestamp lht
                       FROM  vendus
                       WHERE  vendus.id = :id_vendu
                       ');
$req3->execute(array('id_vendu' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           {  if ($donnees3['lht'] !== '0000-00-00 00:00:00'){echo $donnees3['lht'];} }
            $req3->closeCursor(); // Termine le traitement de la requête 3
                ?></td>



          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête

                ?>
       </tbody>
        <tfoot>
          <tr>
            <th></th>

            <th></th>

            <th></th>
            <th></th>
            <th></th>

          </tfoot>

      </table>





  </div><!-- /.container -->

<?php include "pied.php";
 }
    else
{
   header('Location: ../moteur/destroy.php') ;
}
?>
