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

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page:
     if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
      {  include "tete.php" ?>

    <div class="container">
        <h1>Gestion des utilisateurs</h1>
         <ul class="nav nav-tabs">
  <li ><a href="utilisateurs.php">Inscription</a></li>
  <li class="active"><a>Édition</a></li>

</ul>
    <br>

      <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Mail</th>
             <th>Éditer</th>
            <th>Supprimer!</th>

          </tr>
        </thead>
        <tbody>
        <?php
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->query('SELECT * FROM utilisateurs');

           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
            <tr>
            <td><?= $donnees['id']?></td>
            <td><?= $donnees['nom']?></td>
            <td><?= $donnees['prenom']?></td>
            <td><?= $donnees['mail']?></td>
            <td>







<form action="edition_utilisateur.php" method="post">

  <input type="hidden" name ="id" id="id" value="<?= $donnees['id']?>">
  <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']?>">
  <input type="hidden" name ="prenom" id="prenom" value="<?= $donnees['prenom']?>">
  <input type="hidden" name ="mail" id="mail" value="<?= $donnees['mail']?>">
  <input type="hidden" name ="niveau" id="id" value="<?= $donnees['niveau']?>">












   <button  class="btn btn-warning btn-sm ">ÉDITER!
  </button>
</form>
</td>
            <td>







<form action="../moteur/sup_utilisateur.php" method="post">

  <input type="hidden" name ="id" id="id" value="<?= $donnees['id']?>">












   <button  class="btn btn-danger btn-sm ">SUPPRIMER!
  </button>
</form>
</td>
          </tr>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
       </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

          </tfoot>

      </table>
      <br>
      <div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-4"><br> </div>
  <div class="col-md-4"></div>
  </div>
  </div>
  </div>
    </div><!-- /.container -->



<?php include "pied.php";
}
    else
{
   header('Location: ../moteur/destroy.php') ;
}
?>
