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
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
      {  include "tete.php" ?>
    <div class="container">
        <h1>Gestion des utilisateurs</h1>
         <ul class="nav nav-tabs">
  <li class="active"><a>Inscription</a></li>
  <li><a href="edition_utilisateurs.php">Édition</a></li>

</ul>
    <br>
<div class="panel-body">
        <div class="row">
            <form action="../moteur/inscription_post.php" method="post">
  <div class="col-md-2"><label for="nom">Nom:</label> <input type="text" value ="<?= $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus><br>
                        <label for="prenom">Prénom:</label> <input type="text" value ="<?= $_GET['prenom']?>" name="prenom" id="prenom" class="form-control " required><br>
                        <label for="mail">Mail:</label> <input type="email" value ="<?= $_GET['mail']?>" name="mail" id="mail" class="form-control " required ><br>
                        <label>Mot de passe</label> <input type="password"  name="pass1" id="pass1" class="form-control" required ><br>
                                                       Répetez le mot de passe</label> <input type="password"  name="pass2" id="pass2" class="form-control" required >
  </div>
  <div class="col-md-4"><div class="alert alert-info"><label for="niveau">Permissions d'accès</label> <br>

          <input type="checkbox" name="niveaubi" id="niveaubi" value="bi"><label for="niveaubi">Bilans</label><br>

          <input type="checkbox" name="niveaug" id="niveaug" value="g"> <label for="niveaug">Gestion quotidienne</label><br>
          <input type="checkbox" name="niveauh" id="niveauh" value="h"> <label for="niveauh">Verif. formulaires</label><br>
          <input type="checkbox" name="niveaul" id="niveaul" value="l"> <label for="niveaul">Utilisateurs</label><br>
          <input type="checkbox" name="niveauj" id="niveauj" value="j"> <label for="niveauj">Recycleurs et convention partenaires</label><br>
          <input type="checkbox" name="niveauk" id="niveauk" value="k"> <label for="niveauk">Configuration de Oressource</label><br>
       <?php if ($_SESSION['saisiec'] == 'oui'){ ?>
          <input type="checkbox" name="niveaue" id="niveaue" value="e"> <label for="niveaue">Saisir la date dans les formulaires</label><br>
       <?php }?>
<br></div>
  </div>
<div class="col-md-4"><div class="alert alert-info"><label for="niveauc">Points de collecte:</label><br>
<?php
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM points_collecte');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>


            <input type="checkbox" name="niveauc<?= $donnees['id']; ?>" id="niveauc<?= $donnees['id']; ?>"> <?= '<label for="niveauc'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>
</div>

                                            <div class="alert alert-info"><label for="niveauv">Points de vente:</label><br>
          <?php
            // On recupère tout le contenu de la table point de vente
            $reponse = $bdd->query('SELECT * FROM points_vente');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>


            <input type="checkbox" name="niveauv<?= $donnees['id']; ?>" id="niveauv<?= $donnees['id']; ?>"> <?= '<label for="niveauv'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>






              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?></div>
                                            <div class="alert alert-info"><label for="niveaus">Points de sortie hors-boutique:</label><br>
          <?php
            // On recupère tout le contenu de la table point de vente
            $reponse = $bdd->query('SELECT * FROM points_sortie');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>
                     <input type="checkbox" name="niveaus<?= $donnees['id']; ?>" id="niveaus<?= $donnees['id']; ?>"> <?= '<label for="niveaus'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>

              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>












        </div></div>
  <div class="col-md-4"><br></div>




</div>
<div class="row"><div class="col-md-3 col-md-offset-3"><br><button name="creer" class="btn btn-default">Créer!</button></div></div>
      </div>


  </div>



<?php include "pied.php";
}
    else
{
    header('Location: ../moteur/destroy.php') ;
}
?>
