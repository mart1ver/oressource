<?php session_start(); ?>
<?php include "tete.php" ?>
 <p class="text-center"><h1>Point de collecte</h1> 
           </p> 
           <b>nom du point de collecte</b>
<div class="row">
        <div class="col-md-4" >
        	Type collecte?
        <br>
            localité?
        <br>
            adhérent?<input type="checkbox">
        </div>  
        <div class="col-md-4" >
          
         
         
        </div>
        
      </div>
      <div class="row">
      	<br><br><br>
        <div class="col-md-4" >
        
bon d'apport:

<ul class="list-group">
  <li class="list-group-item">
    <span class="badge">masse</span>
    type
  </li>
  <li class="list-group-item">
    <span class="badge">masse</span>
    type
  </li>
  <li class="list-group-item">
    <span class="badge">masse</span>
    type
  </li>
  <li class="list-group-item">
    <span class="badge">masse</span>
    type
  </li>
  <li class="list-group-item">
    <span class="badge">masse</span>
    type
  </li>
  <li class="list-group-item">
    <span class="badge">masse</span>
    type
  </li>
</ul>

        </div>  
          <div class="col-md-4" >
        <label>liste des types de dechets collectés </label><br>
        <?php 
          
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
              
            <button class="btn btn-default btn-lg" data-value="4"><?php echo$donnees['nom']?></button> 
              </a>
          </li>
          <br>
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

        </div>


<div class="col-md-4" >
          <label>Clavier</label><br>
    <div class="col-md-3" style="width: 200px;">
    	<div class="row">
    	<div class="input-group">
  
  <input type="text" class="form-control" placeholder="Masse"><span class="input-group-addon">Kg.</span>
</div>
</div><br>
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
      <br><br><br>
      <div class="row">
        <div class="col-md-4" >
        	<button class="btn btn-default btn-lg" data-value="C">c'est pesé!</button>
            <button class="btn btn-default btn-lg" data-value="0">reset</button>
            <button class="btn btn-default btn-lg" data-value=",">imprimer</button>
        <br>
            
        </div>  
        <div class="col-md-4" >
          
         
         
        </div>
        <div class="col-md-4" >
          
         <button class="btn btn-default btn-lg" data-value="C">remboursement</button>
            <button class="btn btn-default btn-lg" data-value="0">total caisse</button>
         
        </div>
      </div>
<br>
      <?php include "pied.php" ?>
