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
        <h1>Modifier la sortie n° <?= $_GET['nsortie']?></h1>
 <div class="panel-body">




<br>


<div class="row">

          <form action="../moteur/modification_verification_sortiesr_post.php?nsortie=<?= $_GET['nsortie']?>" method="post">
            <input type="hidden" name ="id" id="id" value="<?= $_GET['nsortie']?>">

<input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']?>">
  <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']?>">
    <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']?>">



<div class="col-md-3">

<label for="id_filiere">Nom de l'entreprise de recyclage:</label>
<select name="id_filiere" id="id_filiere" class="form-control " required>
            <?php
            // On affiche une liste deroulante des type de sortie visibles
            $reponse = $bdd->query('SELECT * FROM filieres_sortie WHERE visible = "oui"');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
              if ($_POST['nom'] == $donnees['nom'])  // SI on a pas de message d'erreur
{
  ?>
    <option value = "<?=$donnees['id']?>" selected ><?=$donnees['nom']?></option>
<?php
} else {
            ?>

      <option value = "<?=$donnees['id']?>" ><?=$donnees['nom']?></option>
            <?php }}
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
    </select>

  </div>
  <div class="col-md-3">

    <label for="commentaire">Commentaire</label>



 <textarea name="commentaire" id="commentaire" class="form-control"><?php
            // On affiche le commentaire
            $reponse = $bdd->prepare('SELECT commentaire FROM sorties WHERE id = :id_sortie');
            $reponse->execute(array('id_sortie' => $_GET['nsortie']));
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch()){

           echo $donnees['commentaire'];
             }
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?></textarea>





 </div>
  <div class="col-md-3">

  <br>
<button name="creer" class="btn btn-warning">Modifier</button>
</div>
</form>
</div>



</div>
<h1>Pesées incluses dans cette sortie</h1>
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>Type de déchet:</th>
            <th>Masse</th>
            <th></th>

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

$req = $bdd->prepare('SELECT pesees_sorties.id ,pesees_sorties.timestamp  ,type_dechets_evac.nom  , pesees_sorties.masse ,type_dechets_evac.couleur
                       FROM pesees_sorties ,type_dechets_evac
                       WHERE type_dechets_evac.id = pesees_sorties.id_type_dechet_evac AND pesees_sorties.id_sortie = :id_sortie');
$req->execute(array('id_sortie' => $_GET['nsortie']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr>
            <td><?= $donnees['id']?></td>
            <td><?= $donnees['timestamp']?></td>
            <td><span class="badge" id="cool" style="background-color:<?=$donnees['couleur']?>"><?=$donnees['nom']?></span></td>
            <td><?= $donnees['masse']?></td>





<td>

<form action="modification_verification_pesee_sortiesr.php" method="post">

<input type="hidden" name ="id" id="id" value="<?= $donnees['id']?>">
<input type="hidden" name ="nomtypo" id="nomtypo" value="<?= $donnees['nom']?>">
<input type="hidden" name ="nsortie" id="nsortie" value="<?= $_GET['nsortie']?>">
<input type="hidden" name ="masse" id="masse" value="<?= $donnees['masse']?>">
<input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']?>">

  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






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
