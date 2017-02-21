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

require_once('../core/requetes.php');
require_once('../core/session.php');
require_once('../moteur/dbconfig.php');

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

// Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_sortie_id($numero)) {

  if ($_SESSION['affss'] !== "oui") {
    header("Location:sortiesr.php?numero=" . $numero);
    die();
  }
  require_once('tete.php');

  // Simple formulaire de saisie des matieres d'ouevres sortantes de la structure. (structures partenaires, conventiionnées)
  // Doit etre fonctionnel avec un ecran tactille.

  $point_sortie = point_sorties_id($bdd, $numero);
  $pesee_max = (float) $point_sortie['pesee_max'];
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
                      $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"' );
                      // On affiche chaque entrée une à une
                      while ($donnees = $reponse->fetch())
                      {
                        ?> parseFloat(document.getElementById('<?php echo$donnees['id']?>').value) + <?php
                      }
                      $reponse->closeCursor(); // Termine le traitement de la requête
                      ?><?php 
                      // On obtient tous les visibles de la table type_dechets de manière à cacluler mtot...
                      $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"' );
                      // On affiche chaque entrée une à une
                      while ($donnees = $reponse->fetch())
                      {
                        ?> parseFloat(document.getElementById('d<?php echo$donnees['id']?>').value) + <?php
                      }
                      $reponse->closeCursor(); // Termine le traitement de la requête
                      ?>0; 


      var headstr = "<html><head><title></title></head><body><small><?php echo $_SESSION['structure'] ?><br><?php echo $_SESSION['adresse'] ?><br><label>Bon de sorties au partenaires</label><br>";
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
<?php 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible ="oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    document.getElementById('<?php echo$donnees['nom']?>').textContent = "0"  ;
    document.getElementById('<?php echo$donnees['id']?>').value = "0" ; 
<?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>  

                <?php 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible ="oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    document.getElementById('<?php echo "d".$donnees['nom']?>').textContent = "0"  ;
    document.getElementById('<?php echo "d".$donnees['id']?>').value = "0" ; 
<?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>  



}
</script>
<div class="panel-body">
  <fieldset>
    <legend><?php echo($point_sortie['nom']); ?></legend>
  </fieldset>
<div class="row">
    
        <div class="col-md-7 col-md-offset-1" >

 <ul class="nav nav-tabs">
  <?php if ($_SESSION['affsp'] == "oui"){ ?><li><a href="<?php echo  "sortiesp.php?numero=" . $numero?>">Poubelles</a></li><?php } ?>
  <?php if ($_SESSION['affss'] == "oui"){ ?><li class="active"><a>Sorties partenaires</a></li><?php } ?>
  <?php if ($_SESSION['affsr'] == "oui"){ ?><li><a href="<?php echo  "sortiesr.php?numero=" . $numero?>">Recyclage</a></li><?php } ?>
  <?php if ($_SESSION['affsd'] == "oui"){ ?> <li><a href="<?php echo  "sorties.php?numero=" . $numero?>">Don</a></li><?php } ?>
  <?php if ($_SESSION['affsde'] == "oui"){ ?><li><a href="<?php echo  "sortiesd.php?numero=" . $numero?>">Déchetterie</a></li><?php } ?>
</ul>
    <br>   
</div>
</div>          
<div class="row">
	  
        <div class="col-md-3 col-md-offset-1" >
        	
          <form action="../moteur/sortiesc_post.php" method="post" id="formulaire">
        
          <input type="hidden" name ="id_point_sortie" id="id_point_sortie" value="<?php echo $numero?>">
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
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            





<ul class="list-group">
  <li class="list-group-item">
    <input type="hidden" value="0" name ="<?php echo$donnees['id']?>" id="<?php echo$donnees['id']?>">
    <span class="badge" id="<?php echo$donnees['nom']?>" style="background-color:<?php echo$donnees['couleur']?>">0</span>
    <?php echo$donnees['nom']?>

  </li>






   
              <?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
          
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            






  <li class="list-group-item">
    <input type="hidden" value="0" name ="<?php echo "d".$donnees['id']?>" id="<?php echo "d".$donnees['id']?>">
    <span class="badge" id="<?php echo "d".$donnees['nom']?>" style="background-color:<?php echo$donnees['couleur']?>">0</span>
    <?php echo$donnees['nom']?>

  </li>






   
              <?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

</ul>
 

           
        
</div>
</div>


        </div> 
         <div class="col-md-3" >
           <div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"><label>Nom du partenaire:</label></h3>
  </div>
  <div class="panel-body"> 

          <select name ="id_convention" id ="id_convention" class="form-control " autofocus required>
            <option value = "0" disabled selected></option>


<?php 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM conventions_sorties WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>

  <option value = "<?php echo$donnees['id']?>" ><?php echo$donnees['nom']?></option>
  



     
              
            
             
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>



        




                    
        
          </select>

<br>
 <input type="hidden" value="0" name ="najout" id="najout">
  

 <input type="text" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire">
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
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style=" margin-right:8px;">
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
         


<div class="col-md-3" >
          

<div class="row" >
<div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"><label>Type d'objet:</label></h3>
  </div>
  <div class="panel-body">
<?php
  // On recupère tout le contenu de la table point de collecte
  $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
  // On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
?>
      <div class="btn-group">
      <button class="btn btn-default" style="margin-left:8px; margin-top:16px;"
              onclick="masse_write(
                document.getElementById('<?php echo$donnees['nom']?>'),
                document.getElementById('<?php echo$donnees['id']?>'),
                <?php echo($pesee_max); ?>,
                0.0);">
<span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
 </button>
    </div>
<?php
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
?>
    </div>



    </div>
  </div>

<div class="row" >
<div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"><label>Matériaux et déchets:</label></h3>
  </div>
  <div class="panel-body"> 
      


<?php
  // On recupère tout le contenu de la table point de collecte
  $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"');
  // On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
?>
      <div class="btn-group">
      <button class="btn btn-default" style="margin-left:8px; margin-top:16px;"
              onclick="masse_write(
                document.getElementById('<?php echo"d".$donnees['nom']?>'),
                document.getElementById('<?php echo "d".$donnees['id']?>'),
                <?php echo($pesee_max); ?>,
                0.0);">
<span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
 </button>
    </div>
<?php
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
?>
    </div>
    </div>
  </div>
<button class="btn btn-primary btn-lg" onclick="if (parseInt(document.getElementById('id_convention').value) != 0){verif_form_sortie();}">C'est pesé!</button>

<button class="btn btn-primary btn-lg"  align="center"  onclick="printdiv('divID');" value=" Print " ><span class="glyphicon glyphicon-print"></span></button>
        <button class="btn btn-warning btn-lg" onclick="tdechet_clear();"><span class="glyphicon glyphicon-refresh"></button>
        </div>


      </div>
      <br><br><br>
      
       
      </div>
<br>


<?php include "pied.php";  
} 
else
{ 
        header('Location:../moteur/destroy.php');
}
?>
