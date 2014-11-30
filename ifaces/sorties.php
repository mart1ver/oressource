<?php session_start(); 
 include "tete.php";
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 's'.$_GET['numero']) !== false))
      {
//Oressource 2014, formulaire de sorties hors boutique
//Simple formulaire de saisie des matieres d'ouevres sortantes de la structure. (Dons)
//Doit etre fonctionnel avec un ecran tactille.
//Du javascript permet l'interactivité du keypad et des boutons centraux avec le bon de collecte 
//
//
//
//
//

//on obtient la masse maximum suportée par la balance à ce point de sortie dans la variable $pesee_max
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
            //on obtient le nom du point de collecte designé par $GET['numero']
            $req = $bdd->prepare("SELECT pesee_max FROM points_collecte WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
            // On affiche chaque entree une à une
            while ($donnees = $req->fetch())
            {
            $pesee_max = $donnees['pesee_max'];
            }
            $reponse->closeCursor(); // Termine le traitement de la requête        
  ?>
<script type="text/javascript">
function encaisse() {
  if (parseInt(document.getElementById('najout').value) >= 1) 
          { 
         
          document.getElementById("formulaire").submit();
          }
        }
function printdiv(divID)
    {
      var headstr = "<html><head><title></title></head><body><small><?php echo $_SESSION['structure'] ?><br><?php echo $_SESSION['adresse'] ?><br><label>Bon de sortie hors boutique.</label><br>type d'apport";
      var footstr = "<br>Masse totale :</body></small>";
      var newstr = document.all.item(divID).innerHTML;
      var oldstr = document.body.innerHTML;
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      return false;
    }
function submanut(x)
          {
            if ((document.getElementById("number").value - x) > 0 )
            {
            var text_box = document.getElementById("number");
            text_box.value = text_box.value - x;
          }
          }
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
          if (document.getElementById("number").value > 0 && document.getElementById("number").value < <?php echo $pesee_max;?>) 
          {
             document.getElementById(y).innerText = parseFloat(document.getElementById(y).innerText) + parseFloat(document.getElementById("number").value)  ;
              document.getElementById(z).value = parseFloat(document.getElementById(z).value) + parseFloat(document.getElementById("number").value)  ;
             document.getElementById("number").value = "";  
              document.getElementById("najout").value = parseInt(document.getElementById("najout").value)+1;
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
 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
 
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
<?php
if ($_GET['err'] == "") // SI on a pas de message d'erreur
{
   echo'';
}

else // SINON 
{
  echo'<div class="alert alert-danger">'.$_GET['err'].'</div>';
}


if ($_GET['msg'] == "") // SI on a pas de message positif
{
   echo '';
}

else // SINON (la variable ne contient ni Oui ni Non, on ne peut pas agir)
{
  echo'<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$_GET['msg'].'</div>';
}
?>
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
 
            // On recupère tout le contenu de la table point de collecte
          
            $req = $bdd->prepare("SELECT * FROM points_sortie WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

            echo$donnees['nom'];
            
              
            
             
   
               }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>




       </legend>
      
        
         
     
        
      </fieldset>   
<div class="row">
    
        <div class="col-md-7 col-md-offset-1" >

 <ul class="nav nav-tabs">
  <li><a href="<?php echo  "sortiesp.php?numero=" . $_GET['numero']?>">Poubelles</a></li>
  <li><a href="<?php echo  "sortiesc.php?numero=" . $_GET['numero']?>">Don aux partenaires</a></li>
  <li><a href="<?php echo  "sortiesr.php?numero=" . $_GET['numero']?>">Recyclage</a></li>
  <li class="active"><a>Dons</a></li>
  <li><a href="<?php echo  "sortiesd.php?numero=" . $_GET['numero']?>">Decheterie</a></li>
  
</ul>
    <br>   
</div>
</div>          
<div class="row">
	  <div class="col-md-3 col-md-offset-1" >  	
          <form action="../moteur/sorties_post.php" method="post" id="formulaire">
          
        </div>  
        <div class="col-md-4" >
          
         
         
        </div>
        
      </div>
      <div class="row">
      	<br>
        <div class="col-md-3 col-md-offset-1" >
          <div class="panel panel-info">
        <div class="panel-heading">
          <form action="../moteur/collecte_post.php" method="post">
    <h3 class="panel-title"><label>bon de sortie hors boutique:</label></h3>
  </div>
  <div class="panel-body" id="divID"> 






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
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
    
            





<ul class="list-group">
  <li class="list-group-item">
    <input type="hidden" value="0" name ="<?php echo$donnees['id']?>" id="<?php echo$donnees['id']?>">
     <input type="hidden" value="0" name ="najout" id="najout">
    <span class="badge" id="<?php echo$donnees['nom']?>" style="background-color:<?php echo$donnees['couleur']?>">0</span>
    <?php echo$donnees['nom']?>

  </li>






   
              <?php }

              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>


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
  
            
           
        <br>

</div> 
</div>

        </div> 
         <div class="col-md-3" >
<div class="panel panel-info">
        <div class="panel-heading">
     <h3 class="panel-title"><label>Informations :</label></h3>
  </div>
  <div class="panel-body"> 
 <label for="type_sortie">Type de sortie:</label>
          <select name ="type_sortie" id ="type_sortie" class="form-control" required autofocus>
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
            // On recupère tout le contenu de la table type de sortie
            $reponse = $bdd->query('SELECT * FROM type_sortie WHERE visible = "oui"');
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>

  <option value = "<?php echo$donnees['id']?>" ><?php echo$donnees['nom']?></option>
  



     
            <br>
              
            
             
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>



        




                    
        
          </select>
<br>
<input type="hidden" name ="id_point_sortie" id="id_point_sortie" value="<?php echo $_GET['numero']?>">
<input type="text" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire"><br>
<input name ="adh" id ="adh" type="checkbox" ><label for="adh">Adhérent de l'association</label> <a href="adhesions.php"  target="_blank"><span style="float:right;" class="glyphicon glyphicon-pencil"></span></a>
</div>
</div>

          </form>
         	<br><br>
          <div class="col-md-3" style="width: 220px;" >


  <div class="panel panel-info">
        
  <div class="panel-body"> 
   
      <div class="row">
      

   <div class="input-group">
      <input type="text" class="form-control" placeholder="Masse" id="number" name="num" style=" margin-left:8px; " >
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style=" margin-right:8px; " > <span class="glyphicon glyphicon-minus"></span> <span class="caret"</span></button>
        


        <ul class="dropdown-menu dropdown-menu-right" role="menu">
        
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
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?>
      <div class="btn-group">
      <button class="btn btn-default" style="margin-left:8px; margin-top:16px;" onclick="tdechet_write('<?php echo$donnees['nom']?>','<?php echo$donnees['id']?>');" ><span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
 </button>
      
    </div>
   
                <?php }
                $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    </div> 



    </div>
  </div>

<div class="row" >
<div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"><label>Materiaux et dechets:</label></h3>
  </div>
  <div class="panel-body"> 
      


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
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets_evac WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?>
      <div class="btn-group">
      <button class="btn btn-default" style="margin-left:8px; margin-top:16px;" onclick="tdechet_write('<?php echo"d".$donnees['nom']?>','<?php echo "d".$donnees['id']?>');" ><span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
 </button>
      
    </div>
   
                <?php }
                $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    </div> 



    </div>
  </div>
<button class="btn btn-primary btn-lg" onclick="encaisse();">C'EST PESÉ!</button>

<button class="btn btn-primary btn-lg"  align="center"  onclick="printdiv('divID');" value=" Print " ><span class="glyphicon glyphicon-print"></span></button>
        <button class="btn btn-warning btn-lg" onclick="tdechet_clear();"><span class="glyphicon glyphicon-refresh"></button>
        </div>


      </div>
      <br><br><br>
      
       
      </div>
<br>


      <?php include "pied.php";  } else
      { 
        header('Location:../moteur/destroy.php');
      }?>