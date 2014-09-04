<?php session_start();

 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'c'.$_GET['numero']) !== false))
      {include "tete.php";
//Oressource 2014, formulaire de collecte
//Simple formulaire de saisie des matieres d'ouevres entrantes dans la structure.
//Doit etre fonctionnel avec un ecran tactille.
//Du javascript permet l'interactivité du keypad et des boutons centraux avec le bon de collecte 
//
//
//
//
//

//on obtien la masse maximum suporté par la balance à ce point de collecte dans la variable $pesee_max
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
                      // On obtient tout les visibles de la table type_dechets de maniere à remetre à zero tout les items du bon d'apport vollontaire
                      $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"' );
                      // On affiche chaque entree une à une
                      while ($donnees = $reponse->fetch())
                      {?>
               document.getElementById("<?php echo$donnees['nom']?>").innerText = "0"  ;
               document.getElementById(<?php echo$donnees['id']?>).value = "0" ; 
                      <?php
                      }
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
<br>
     
       <legend>
        <blockquote>
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
            //on obtient le nom du point de collecte designé par $GET['numero']
            $req = $bdd->prepare("SELECT * FROM points_collecte WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
            // On affiche chaque entree une à une
            while ($donnees = $req->fetch())
            {
            echo$donnees['nom'];
            }
            $reponse->closeCursor(); // Termine le traitement de la requête
        ?>
      </blockquote>
       </legend>
       
      

<div class="row">
  <div class="col-md-3 col-md-offset-1" >
    <form action="../moteur/collecte_post.php" method="post">
    <label for="id_type_collecte">Type de collecte:</label>  
    <select name ="id_type_collecte" id ="id_type_collecte" class="form-control" autofocus >
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
            // On affiche une liste deroulante des type de collecte visibles
            $reponse = $bdd->query('SELECT * FROM type_collecte WHERE visible = "oui"');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
            ?>
      <option value = "<?php echo$donnees['id']?>" ><?php echo$donnees['nom']?></option>
            <?php }
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
    </select>
  </div>
   
<div class="col-md-3" >
<label for="loc">Localité:</label>  
    <select name ="loc" id ="loc" class="form-control">
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
            // On affiche une liste deroulante des localités visibles
            $reponse = $bdd->query('SELECT * FROM localites WHERE visible = "oui"');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
            ?>

      <option value = "<?php echo$donnees['id']?>" ><?php echo$donnees['nom']?></option>
            <?php }
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
    </select>
    
</div>
<br> 
 <div class="col-md-3 " >
   <br>
    <input type="hidden" name ="id_point_collecte" id="id_point_collecte" value="<?php echo $_GET['numero']?>">
    <input name ="adh" id ="adh" type="checkbox" ><label for="adh">Adhére à l'association</label> <a href="adhesions.php"  target="_blank"><span style="float:right;" class="glyphicon glyphicon-pencil"></span></a>
  </div> 
  </div>

<div class="row">
<br>
  <div class="col-md-3 col-md-offset-1" >
  <label>bon d'apport:</label>
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
 
            // on affiche le bon de sortie hors boutique (à zero) correspondant aux types de dechets visibles
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
            ?>
    </ul>
    <input type="hidden" value="0" name ="najout" id="najout">
  <button class="btn btn-primary btn-lg">c'est pesé!</button>
</form>
<button class="btn btn-primary btn-lg"  align="center"><span class="glyphicon glyphicon-print"></span></button>
        <button class="btn btn-warning btn-lg" onclick="tdechet_clear();"><span class="glyphicon glyphicon-refresh"></button>

  <br>
  </div>  
  <div class="col-md-2" >
  <label>Types d'objets collectés:</label><br>
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
 
            // ON AFFICHE un bouton par type de dechet visible(id impair)
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui" AND MOD(id,2)=1');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
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
            // ON AFFICHE un bouton par type de dechet visible(id pair)
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui" AND MOD(id,2)=0');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
            ?>
  <button class="btn btn-default btn-sm" onclick="tdechet_write('<?php echo$donnees['nom']?>','<?php echo$donnees['id']?>');" ><span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
 </button> 
  <br><br>
   
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
      </div>
      <br>
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
</div>
<br><br>   


      <?php include "pied.php";?> 



       <?php } else
      { 
        header('Location:../moteur/destroy.php');
      }?>
