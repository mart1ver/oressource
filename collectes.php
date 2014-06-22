<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos(md5($_SESSION['niveau']), 'e') !== false))
      {
      include('menu.php') ;
?>

      <div class="row">
        <div class="col-md-4" >
        </div>  
        <div class="col-md-4" >
          <p class="text-center"><h4><?php echo $_GET['nom'];?>-<?php echo $_GET['adresse'];?></h4> 
          </p> 
         
        </div>
      </div>
      <div class="row">
        <div class="col-md-3" >








        </div>  
          <div class="col-md-4" >
            <form action="../moteur/collecte_post.php" method="post">
            

            <input name="point" value="<?php echo $_GET['numero'];?>" type="hidden">

            <div class="row">
            <div class="col-xs-5 ">
              <li class="list-group-item">Localisation geographique:<select class="form-control" name="localisation" id="localisation">
                  <option selected="selected">N.R.</option>
                  <option>Proche</option>
                  <option>75001</option>
                  <option>75002</option>
                  <option>75003</option>
                  <option>75004</option>
                  <option>75005</option>
                  <option>75006</option>
                  <option>75007</option>
                  <option>75008</option>
                  <option>75009</option>
                  <option>75010</option>
                  <option>75011</option>
                  <option>75012</option>
                  <option>75013</option>
                  <option>75014</option>
                  <option>75015</option>
                  <option>75016</option>
                  <option>75017</option>
                  <option>75018</option>
                  <option>75019</option>
                  <option>75020</option>
                  <option>autre</option>
                  </select></li>
            </div>
            <div class="col-xs-3">
              <li class="list-group-item">Sexe:<select class="form-control" name="sexe" id="sexe">
                  <option selected="selected">N.R.</option>
                  <option>Femme</option>
                  <option>Homme</option>
                  </select></li>
            </div>
            <div class="col-xs-3">
              <li class="list-group-item">Adherent?:<select class="form-control" name="adherent" id="adherent">
                  <option selected="selected">Non</option>
                  <option>Oui</option>
                  </select></li>
            </div>
            </div>
            <div class="row">
  <div class="col-xs-5 col-md-offset-4">

              <li class="list-group-item">collecte à domicile?:<select class="form-control" name="domicile" id="domicile">
                  <option selected="selected">N.R.</option>
                  <option>Oui</option>
                  <option>Non</option>
                  </select></li>

              <li class="list-group-item">Electromenager/&eacutelectronique:<input type="number" step="any" class="form-control " id="d3e" name="d3e" placeholder="d3e"></li>

              <li class="list-group-item">Mobilier:<input type="number" step="any" class="form-control" id="mobilier" name="mobilier" placeholder="mobilier"></li>

              <li class="list-group-item">Textile/accesoires/bijoux:<input type="number" step="any" class="form-control" id="vetmements" name="vetmements" placeholder="vetmements"></li>

              <li class="list-group-item">Vaisselle:<input type="number" step="any" class="form-control" id="vaisselle" name="vaisselle" placeholder="vaisselle"></li>

              <li class="list-group-item">Bibelots/quincaillerie:<input type="number" step="any" class="form-control" id="bibelots" name="bibelots" placeholder="bibelots"></li>

              <li class="list-group-item">Livres:<input type="number" step="any" class="form-control" id="livres" name="livres" placeholder="livres"></li>

              <li class="list-group-item">Supports media:<input type="number" step="any" class="form-control" id="media" name="media" placeholder="media"></li>

              <li class="list-group-item">Jouets:<input type="number" step="any" class="form-control" id="jouets" name="jouets" placeholder="jouets"></li>

              <li class="list-group-item">Inclassables:<input type="number" step="any" class="form-control" id="autres" name="autres" placeholder="autres"></li>

              <li class="list-group-item">Commentaire: <textarea class="form-control" rows="2" id="comm" name="comm" placeholder="commmentaire"></textarea></li>

              <li class="list-group-item"><button type="submit" class="btn btn-default">C'est pes&eacute!</button></li>
              </div>
</div>
            </form>
        </div>




<div class="row">
        <div class="col-md-4  col-md-offset-1" >

<h1>Clavier</h1>
    <div class="col-md-3" style="width: 200px;">
        <div class="row">
            <button class="btn btn-default btn-lg" data-value="1">1</button>
            <button class="btn btn-default btn-lg" data-value="2">2</button>
            <button class="btn btn-default btn-lg" data-value="3">3</button>
        </div>
        <div class="row">
            <button class="btn btn-default btn-lg" data-value="4">4</button>
            <button class="btn btn-default btn-lg" data-value="5">5</button>
            <button class="btn btn-default btn-lg" data-value="6">6</button>
        </div>
        <div class="row">
            <button class="btn btn-default btn-lg" data-value="1">7</button>
            <button class="btn btn-default btn-lg" data-value="2">8</button>
            <button class="btn btn-default btn-lg" data-value="3">9</button>
        </div>
        <div class="row">
            <button class="btn btn-default btn-lg" data-value="C">C</button>
            <button class="btn btn-default btn-lg" data-value="0">0</button>
            <button class="btn btn-default btn-lg" data-value=",">,</button>
        </div>
    </div>
    




















        </div>  





      </div>


      

<?php if(strpos($_SESSION['niveau'], 'e') !== false)
          {
          
            try
            {
            // On se connecte à MySQL
            include('../moteur/dbconfig.php');
            }
            catch(Exception $e)
            {
            // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
            }
 
            // Si tout va bien, on peut continuer
 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            <li>
              
            <?php echo '<button class="btn btn-default btn-lg" data-value="4">'.$donnees['nom'].'</button>'; ?> 
              </a>
          </li>
          <br>
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                } ?>




</body></html>
<?php }
    else
    header('Location: ../') ;
?>
