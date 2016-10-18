<?php session_start(); 

require_once('../moteur/dbconfig.php');




if($_SESSION['affsr'] !== "oui"){

   header("Location:sorties.php?numero=" . $_GET['numero']);
}

 
//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 's'.$_GET['numero']) !== false))
      {include "tete.php";
//Oressource 2014, formulaire de sorties hors-boutique
//Simple formulaire de saisie des matieres d'ouevres sortantes de la structure. (entreprise de recyclage)
//Doit etre fonctionnel avec un ecran tactille.
//Du javascript permet l'interactivité du keypad et des boutons centraux avec le bon de collecte 
//
//
//
//
//
        //on obtien la masse maximum suporté par la balance à ce point de sortie dans la variable $pesee_max
            //on obtient le nom du point de collecte designé par $GET['numero']
            $req = $bdd->prepare("SELECT pesee_max FROM points_sortie WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
            // On affiche chaque entree une à une
            while ($donnees = $req->fetch())
            {
            $pesee_max = $donnees['pesee_max'];
            }
            $reponse->closeCursor(); // Termine le traitement de la requête        
  ?>
<script src="../js/utilitaire.js"></script>
<script type="text/javascript">
"use strict";
function printdiv(divID)
    {
       if (parseInt(document.getElementById('najout').value) >= 1) 
          { 
      var mtot =<?php 
                      // On obtient tous les visibles de la table type_dechets de manière à cacluler mtot...
                      $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"' );
                      // On affiche chaque entrée une à une
                      while ($donnees = $reponse->fetch())
                      {
                        ?> parseFloat(document.getElementById('m<?php echo$donnees['id']?>').value) + <?php
                      }
                      $reponse->closeCursor(); // Termine le traitement de la requête
                      ?>0; 

      var headstr = "<html><head><title></title></head><body><small><?php echo $_SESSION['structure'] ?><br><?php echo $_SESSION['adresse'] ?><br><label>Bon de sortie recyclage</label><br>";
          var footstr = "<br>Masse totale : "+mtot+" Kgs.</body></small>";
      var newstr = document.all.item(divID).innerHTML;
      var oldstr = document.body.innerHTML;

            
          document.getElementById("formulaire").submit();
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      return false;
          }
    }

function tdechet_clear()
{
  document.getElementById("id_filiere").value = ""
   document.getElementById("id_type_dechet").value = ""
    document.getElementById("type_dechet").value = ""
    document.getElementById("sel_filiere").disabled = false;
    document.getElementById("number").value = "";
<?php 
          
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    document.getElementById('<?php echo$donnees['id']?>').textContent = "0"  ;
    document.getElementById('m'+<?php echo$donnees['id']?>).value = "0" ; 
<?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>  
}
</script>
<div class="panel-body">
          <fieldset>
       <legend>
        <?php 
          
            // On recupère tout le contenu de la table point de collecte
          
            $req = $bdd->prepare("SELECT * FROM points_sortie WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entree une à une
           $donnees = $req->fetch();
           echo$donnees['nom'];
           $reponse->closeCursor(); // Termine le traitement de la requête
                ?>




       </legend>
      
        
         
     
        
      </fieldset>   
<div class="row">
    
        <div class="col-md-7 col-md-offset-1" >

 <ul class="nav nav-tabs">
  
   

    <?php if ($_SESSION['affsp'] == "oui"){ ?><li><a href="<?php echo  "sortiesp.php?numero=" . $_GET['numero']?>">Poubelles</a></li><?php } ?>
    <?php if ($_SESSION['affss'] == "oui"){ ?><li><a href="<?php echo  "sortiesc.php?numero=" . $_GET['numero']?>">Sorties partenaires</a></li><?php } ?>
   <?php if ($_SESSION['affsr'] == "oui"){ ?> <li class="active"><a>Recyclage</a></li><?php } ?>
   <?php if ($_SESSION['affsd'] == "oui"){ ?> <li><a href="<?php echo  "sorties.php?numero=" . $_GET['numero']?>">Don</a></li><?php } ?>
    <?php if ($_SESSION['affsde'] == "oui"){ ?><li><a href="<?php echo  "sortiesd.php?numero=" . $_GET['numero']?>">Déchetterie</a></li><?php } ?>
</ul>
    
</div>
</div>          
<div class="row">
	  
        <div class="col-md-3 col-md-offset-1" >
        	
          <form action="../moteur/sortiesr_post.php" method="post" ONSUBMIT="EnableControl(true)" id="formulaire">
        
        
         
<br>
<input type="hidden" id="id_filiere" name="id_filiere">
<input type="hidden" id="id_type_dechet" name="id_type_dechet">
<input type="hidden" id="type_dechet" name="type_dechet">
<input type="hidden" name="commentaire" id="commentaire" >
 <input type="hidden" value="0" name ="najout" id="najout">
          <input type="hidden" name ="id_point_sortie" id="id_point_sortie" value="<?php echo $_GET['numero']?>">
          <input type="hidden" id="id_user" name="id_user" value=<?php echo'"'.$_SESSION['id'].'"' ?>  >
    <input type="hidden" id="saisiec_user" name="saisiec_user" value=<?php echo'"'.$_SESSION['saisiec'].'"' ?>  >
    <input type="hidden" id="niveau_user" name="niveau_user" value=<?php echo'"'.$_SESSION['niveau'].'"' ?>  >
        </div>  
        <div class="col-md-4" >
          
         
         
        </div>
        
      </div>
      <div class="row">
      	<br>
        <div class="col-md-3 col-md-offset-1" >
           <div class="panel panel-info">
        <div class="panel-heading">           
    <h3 class="panel-title"><label>Bon de sortie hors-boutique: <span id="massetot" >0</span> Kgs.</label></h3>
  </div>
  <?php if ($_SESSION['saisiec'] == 'oui' AND (strpos($_SESSION['niveau'], 'e') !== false) ){ ?>
  <br>
      <p align="center">   Date de la sortie:  <input type="date" id="antidate" name="antidate" style="width: 130px;height:20px;" value=<?php echo date("Y-m-d") ?>>
<br>
</p>
<?php }?>
  <div class="panel-body" id="divID"> 





<?php 
          
            // On recupère tout le contenu de la table type dechets
            $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            





<ul class="list-group">
  <li class="list-group-item">
   <input type="hidden" value="0" name ="<?php echo "d".$donnees['id']?>" id="<?php echo "d".$donnees['id']?>">
    <span class="badge" id="d<?php echo$donnees['nom']?>"style="background-color:<?php echo$donnees['couleur']?>">0</span>
    <?php echo$donnees['nom']?>

  </li>






   
              <?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

</ul>


           
        <br>

  </div> 
</div>

        </div> 
         <div class="col-md-3" >
           <div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"><label for="id_filiere">Nom de l'entreprise de recyclage</label></h3>
  </div>
  <div class="panel-body"> 
           

          <select name ="sel_filiere" id ="sel_filiere" class="form-control " autofocus required>
            <option value = "0" disabled selected></option>


<?php 
          
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT filieres_sortie.id ,filieres_sortie.nom , filieres_sortie.id_type_dechet_evac, type_dechets_evac.nom AS type_dechet

FROM filieres_sortie , type_dechets_evac

WHERE filieres_sortie.visible = "oui" AND filieres_sortie.id_type_dechet_evac = type_dechets_evac.id');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>

  <option value = "<?php echo$donnees['id']?>|<?php echo$donnees['id_type_dechet_evac']?>|<?php echo$donnees['type_dechet']?>" ><?php echo$donnees['nom']?></option>
 

     
              
            
             
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>



        

                    
        
          </select>
    <br>   

  <input type="text" class="form-control" name="commentaireini" id="commentaireini" placeholder="Commentaire" onchange="recocom()">
</form>
</div>
</div>

<br>
  <div class="col-md-3" style="width: 220px;" >


  <div class="panel panel-info">
        
  <div class="panel-body"> 
   
      <div class="row">
      

   <div class="input-group">
      <input type="text" class="form-control" placeholder="Masse" id="number" name="num" style="margin-left:8px;">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="margin-right:8px;">
          <span class="glyphicon glyphicon-minus"></span>
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
  <?php 
            // On affiche une liste déroulante des localités visibles
            $reponse = $bdd->query('SELECT * FROM type_contenants WHERE visible = "oui"');
            // On affiche chaque entrée une à une
            while ($donnees = $reponse->fetch())
            {
            ?>
      <li><a href="#"  onClick="submanut('<?php echo$donnees['masse']?>');"><?php echo$donnees['nom']?></a></li>
     
           
            <?php }
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?>


          
                




                  </ul>
      </div><!-- /btn-group -->
    </div><!-- /input-group -->



      </div>
      <br>
    <div class="row">
      <button class="btn btn-default btn-lg" onclick="number_write('1');" data-value="1" style="margin-left:8px; margin-top:8px;">1</button>
      <button class="btn btn-default btn-lg" onclick="number_write('2');" data-value="2" style="margin-left:8px; margin-top:8px;">2</button>
      <button class="btn btn-default btn-lg" onclick="number_write('3');" data-value="3" style="margin-left:8px; margin-top:8px;">3</button>
    </div>
    <div class="row">
      <button class="btn btn-default btn-lg" onclick="number_write('4');" data-value="4" style="margin-left:8px; margin-top:8px;">4</button>
      <button class="btn btn-default btn-lg" onclick="number_write('5');" data-value="5" style="margin-left:8px; margin-top:8px;">5</button>
      <button class="btn btn-default btn-lg" onclick="number_write('6');" data-value="6" style="margin-left:8px; margin-top:8px;">6</button>
    </div>
    <div class="row">
      <button class="btn btn-default btn-lg" onclick="number_write('7');" data-value="7" style="margin-left:8px; margin-top:8px;">7</button>
      <button class="btn btn-default btn-lg" onclick="number_write('8');" data-value="8" style="margin-left:8px; margin-top:8px;">8</button>
      <button class="btn btn-default btn-lg" onclick="number_write('9');" data-value="9" style="margin-left:8px; margin-top:8px;">9</button>
    </div>
    <div class="row">
      <button class="btn btn-default btn-lg" onclick="number_clear();" data-value="C" style="margin-left:8px; margin-top:8px;">C</button>
      <button class="btn btn-default btn-lg" onclick="number_write('0');" data-value="0" style="margin-left:8px; margin-top:8px;">0</button>
      <button class="btn btn-default btn-lg" onclick="number_write('.');" data-value="," style="margin-left:8px; margin-top:8px;">,</button>
    </div>

 

</div>
</div>



  </div>

         	

           
           </div> 
          


<div class="col-md-2"  style="width: 320px;" >
        
        <div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"><label>Ajouter la pesée</label></h3>
  </div>
  <div class="panel-body" style:"text-align:center"> 

  
  <button class="btn btn-default " onclick="if (parseInt(document.getElementById('sel_filiere').value) != 0){tdechet_add(<?php echo($pesee_max); ?>);}" style="width:260px;height:130px;" ><span class="glyphicon glyphicon-plus"></span></button>

<?php
    // On recupère tout le contenu de la table point de collecte
  $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"');
  // On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
?>
      <div class="btn-group">
      <button class="btn btn-default" style="margin-left:8px; margin-top:16px;"
              onclick="
              masse_write(if (parseInt(document.getElementById('sel_filiere').value) != 0){
                       document.getElementById('<?php echo"d".$donnees['nom']?>'),
                       document.getElementById('<?php echo "d".$donnees['id']?>'),
                       <?php echo($pesee_max); ?>,
                       0.0);}


                       ">
<span class="badge" id="cool"
 style="background-color:<?php echo$donnees['couleur']?>"><?php echo $donnees['nom']?></span>
 </button>
    </div>
                <?php }
                $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

</div>
</div>


        
<div class="row">
  <br>&nbsp;&nbsp;&nbsp;&nbsp;






<button class="btn btn-primary btn-lg"  onclick="if (parseInt(document.getElementById('sel_filiere').value) != 0){verif_form_sortie();}">C'est pesé!</button>
<button class="btn btn-primary btn-lg"  align="center"  onclick="printdiv('divID');" value=" Print " ><span class="glyphicon glyphicon-print"></span></button>
        <button class="btn btn-warning btn-lg" onclick="tdechet_clear();"><span class="glyphicon glyphicon-refresh"></button>
      </div>

</div>

      </div>

      <br><br>
      
       
      </div>
<br>


      <?php include "pied.php";  
} else
      { 
        header('Location:../moteur/destroy.php');
      }
?>
