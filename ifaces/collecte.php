<?php session_start(); ?>
<?php include "tete.php" ?>








<script type="text/javascript">
function number_write(x)
{
  var text_box = document.getElementById("number");
  
   
 text_box.value = text_box.value + x;
  
}

function number_clear()
{
  document.getElementById("number").value = "";
}
function tdechet_write(y)
{





  
document.getElementById(y).innerText = parseFloat(document.getElementById(y).innerText) + parseFloat(document.getElementById("number").value)  ;
document.getElementById("number").value = "";

 
  
}
function tdechet_clear()
{



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
    
            





document.getElementById('<?php echo$donnees['nom']?>').innerText = "0"  ;






   
              <?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>



  


 
  
}

</script>











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
    
            





<ul class="list-group">
  <li class="list-group-item">
   
    <span class="badge" id="<?php echo$donnees['nom']?>">0</span>
    <?php echo$donnees['nom']?>

  </li>






   
              <?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

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
    
            
              
            <button class="btn btn-default btn-sm" onclick="tdechet_write('<?php echo$donnees['nom']?>');" ><?php echo$donnees['nom']?></button> 
              <br>
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
  
  <input type="text" class="form-control" placeholder="Masse" id="number" name="num"  ><span class="input-group-addon">Kg.</span>
</div>
</div><br>
        <div class="row">
            <button class="btn btn-default btn-lg" onclick="number_write('1');" data-value="1">1</button>
            <button class="btn btn-default btn-lg" onclick="number_write('2');" data-value="2">2</button>
            <button class="btn btn-default btn-lg" onclick="number_write('3');" data-value="3">3</button>
        </div>
        <div class="row">
            <button class="btn btn-default btn-lg" onclick="number_write('4');" data-value="4">4</button>
            <button class="btn btn-default btn-lg" onclick="number_write('5');" data-value="5">5</button>
            <button class="btn btn-default btn-lg" onclick="number_write('6');" data-value="6">6</button>
        </div>
        <div class="row">
            <button class="btn btn-default btn-lg" onclick="number_write('7');" data-value="7">7</button>
            <button class="btn btn-default btn-lg" onclick="number_write('8');" data-value="8">8</button>
            <button class="btn btn-default btn-lg" onclick="number_write('9');" data-value="9">9</button>
        </div>
        <div class="row">
            <button class="btn btn-default btn-lg" onclick="number_clear();" data-value="C">C</button>
            <button class="btn btn-default btn-lg" onclick="number_write('0');" data-value="0">0</button>
            <button class="btn btn-default btn-lg" onclick="number_write('.');" data-value=",">,</button>
        </div>














    </div>
        </div>


      </div>
      <br><br><br>
      <div class="row">
        <div class="col-md-4" >
        	<button class="btn btn-default btn-lg" data-value="C">c'est pesé!</button>
            <button class="btn btn-default btn-lg" onclick="tdechet_clear();" data-value="0">reset</button>
            <button class="btn btn-default btn-lg" data-value=",">imprimer</button>
        <br>
            
        </div>  
        <div class="col-md-4" >
          
         
         
        </div>
        <div class="col-md-4" >
          
        
         
        </div>
      </div>
<br>
      <?php include "pied.php" ?>
