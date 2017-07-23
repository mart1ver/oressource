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
   if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND $_SESSION['viz_caisse'] = "oui" AND (strpos($_SESSION['niveau'], 'v'.$_GET['numero']) !== false)  )
      {  include "tete.php"
?>
   <div class="container">
        <h1>Visualiser la vente n° <?= $_GET['nvente']?></h1>
        <p align="right">
        <input class="btn btn-default btn-lg" type='button'name='quitter' value='Quitter' OnClick="window.close();"/></p>
 <div class="panel-body">




<br>





</div>
<h1>Objets inclus dans cette vente</h1>
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




          </tr>
        </thead>
        <tbody>


        <?php

$req = $bdd->prepare('SELECT
vendus.id ,vendus.timestamp,
type_dechets.nom type,
IF(vendus.id_objet > 0 ,grille_objets.nom, "autre") objet,
vendus.quantite ,
vendus.prix,
utilisateurs.mail
FROM
vendus, type_dechets, grille_objets ,utilisateurs
WHERE
vendus.id_vente = :id_vente
AND type_dechets.id = vendus.id_type_dechet
AND (grille_objets.id = vendus.id_objet OR vendus.id_objet = 0 )
AND utilisateurs.id = vendus.id_createur
GROUP BY id');
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
<td><?= $donnees['prix']?></td>
<td><?= $donnees['mail']?></td>






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
