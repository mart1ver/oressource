<?php session_start(); ?>
<?php include "tete.php" ?>
<div class="row">
        <div class="col-md-4" >
        </div>  
        <div class="col-md-4" >
          
          <p class="text-center"><h1>Formulaire d'adhésion</h1> 
           </p> 
          <p class="text-center"><b>L'adhésion à la ressourcerie formalise avant tout votre soutien aux valeurs écologiques et sociales défendues par l'association. (Et peut, par ailleurs, s'avérer utile pour être tenu informé par courriel des diverses activités, ponctuelles ou ordinaires, développées la ressourcerie.) Adhérer est donc surtout un geste politique, militant, d'engagement actif dans la lutte contre l'absurdité consumériste et sa normalisation du gaspillage!!</b>
        </p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4" >
        </div>  
          <div class="col-md-4" >
            <form action="/oressource/moteur/adhesion_post.php" method="post">
              <ul class="list-group" >
                <li class="list-group-item">Nom:<input type="text" class="form-control" id="nom" name="nom" placeholder="nom"></li>
                <li class="list-group-item">Prénom:<input type="text" class="form-control" id="prenom" name="prenom" placeholder="prenom"></li>
                <li class="list-group-item">Date de naissance:<input type="date" class="form-control" id="date_de_naissance" name="date_de_naissance" placeholder="Date de naissance"></li>
                <li class="list-group-item">Sexe:<select class="form-control" name="sexe" id="sexe">
                  <option selected="selected">N.R.</option>
                  <option>Femme</option>
                  <option>Homme</option>
                  </select></li>
                <li class="list-group-item">Adresse E-mail: <input type="email" class="form-control" id="mail" name="mail" placeholder="Email"></li>
                <li class="list-group-item">Adresse postale: <textarea class="form-control" rows="4" id="adresse" name="adresse" placeholder="addresse"></textarea></li>
                <li class="list-group-item">Numéro de téléphone:<input type="tel" class="form-control" id="telephone" name="telephone" placeholder="telephone"></li>
                <li class="list-group-item">Commentaires:<textarea class="form-control" rows="3" id="comm" name="comm" placeholder="commentaire"></textarea></li>
                <li class="list-group-item"><button type="submit" class="btn btn-default">J'adhère!</button></li>
              </ul>
            </form>
        </div>
      </div>
      <?php include "pied.php" ?>
