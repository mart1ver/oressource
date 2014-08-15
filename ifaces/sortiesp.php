<?php session_start(); 
 include "tete.php";
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 's'.$_GET['numero']) !== false))
      {
//Oressource 2014, formulaire de sorties hors boutique
//Simple formulaire de saisie des matieres d'ouevres sortantes de la structure. (poubelles)
//Doit etre fonctionnel avec un ecran tactille.
//Du javascript permet l'interactivité du keypad et des boutons centraux avec le bon de collecte 
//
//
//
//
//
  ?>
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
function tdechet_write(y,z)
{

if (document.getElementById("number").value-parseFloat(document.getElementById("m"+y).value)  > 0) 
{
    document.getElementById(y).innerText = (parseFloat(document.getElementById(y).innerText) + parseFloat(document.getElementById("number").value))-parseFloat(document.getElementById("m"+y).value)  ;
     document.getElementById(z).value = parseFloat(document.getElementById(z).value) + parseFloat(document.getElementById("number").value)-parseFloat(document.getElementById("m"+y).value)  ;
    document.getElementById("number").value = "";  
}


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
 
            // On recupère tout le contenu de la table types_poubelles
            $reponse = $bdd->query('SELECT * FROM types_poubelles');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    document.getElementById('<?php echo$donnees['nom']?>').innerText = "0"  ;
    document.getElementById(<?php echo$donnees['id']?>).value = "0" ;
<?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>  
}
</script>
<div class="panel-body">
          <fieldset>
       <legend>
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
 
            // On recupère tout le contenu de la table points_sortie
          
            $req = $bdd->prepare("SELECT * FROM points_sortie WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

            echo$donnees['nom'];
            
              
            
             
   
               }
              $req->closeCursor(); // Termine le traitement de la requête
                ?>




       </legend>
      
        
         
     
        
      </fieldset>   
<div class="row">
    
        <div class="col-md-5 col-md-offset-1" >

 <ul class="nav nav-tabs">
  <li><a href="<?php echo  "sorties.php?numero=" . $_GET['numero']?>">Dons</a></li>
  <li><a href="<?php echo  "sortiesc.php?numero=" . $_GET['numero']?>">Don aux partenaires</a></li>
  <li><a href="<?php echo  "sortiesr.php?numero=" . $_GET['numero']?>">Recyclage</a></li>
  <li class="active"><a>Poubelles</a></li>
</ul>
    <br>   
</div>
</div>          
<div class="row">
	  
        <div class="col-md-4 col-md-offset-1" >
        	
          <form action="../moteur/sortiesp_post.php" method="post">
              <input type="hidden" name ="id_point_sortie" id="id_point_sortie" value="<?php echo $_GET['numero']?>">
       <p>La masse de chaque bac est automatiquement déduite.</p>  
         
<br>
          
        </div>  
        <div class="col-md-4" >
          
         
         
        </div>
        
      </div>
      <div class="row">
      	<br>
        <div class="col-md-3 col-md-offset-1" >
        
<label>bon de sortie poubelles:</label>




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
            $reponse = $bdd->query('SELECT * FROM types_poubelles WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            





<ul class="list-group">
  <li class="list-group-item">
    <input type="hidden" value="0" name ="<?php echo$donnees['id']?>" id="<?php echo$donnees['id']?>">
    <span class="badge" id="<?php echo$donnees['nom']?>"style="background-color:<?php echo$donnees['couleur']?>">0</span>
    <?php echo$donnees['nom']?>

  </li>






   
              <?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

</ul>
  <button class="btn btn-default btn-lg">c'est pesé!</button></form>
            <button class="btn btn-default btn-lg" onclick="tdechet_clear();" >reset</button>
           
        <br>



        </div> 
         <div class="col-md-2" >
         	<label>types de bacs de sortie poubelles:</label><br>
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
            $reponse = $bdd->query('SELECT * FROM types_poubelles WHERE visible = "oui" AND MOD(id,2)=1');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            
            <input type="hidden" name ="m<?php echo $donnees['nom']?>" id="m<?php echo $donnees['nom']?>" value="<?php echo $donnees['masse_bac']?>">
            <button class="btn btn-default btn-sm" onclick="tdechet_write('<?php echo$donnees['nom']?>','<?php echo$donnees['id']?>');" ><span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
            </button> 
              <br>
          <br>
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
         	 </div> 
          <div class="col-md-2" >
        <br><br>        
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
            $reponse = $bdd->query('SELECT * FROM types_poubelles WHERE visible = "oui" AND MOD(id,2)=0');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            
              
            <input type="hidden" name ="m<?php echo $donnees['nom']?>" id="m<?php echo $donnees['nom']?>" value="<?php echo $donnees['masse_bac']?>">
            <button class="btn btn-default btn-sm" onclick="tdechet_write('<?php echo$donnees['nom']?>','<?php echo$donnees['id']?>');" ><span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
            </button> 
              <br>
          <br>
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

        </div>


<div class="col-md-3" >
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
      
       
      </div>
<br>


      <?php include "pied.php";  } else
      { 
        header('Location:../moteur/destroy.php');
      }?>