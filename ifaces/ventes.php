<?php session_start(); 
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'v'.$_GET['numero']) !== false))
      {include "tete.php";?>


<div class="panel-body">
      <fieldset>
      <legend>
        <?php 
          // on determine le numero de la vente
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
 
         $req = $bdd->prepare("SELECT max(id) FROM ventes WHERE id_point_vente = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {
            $numero_vente = $donnees['max(id)'] + 1;
           }
              $req->closeCursor(); // Termine le traitement de la requête
            //on affiche le nom du point de vente
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
          
            $req = $bdd->prepare("SELECT * FROM points_vente WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {
            echo$donnees['nom'];
            $nom_pv = $donnees['nom'];
            $adresse_pv = $donnees['adresse'];
           }
              $req->closeCursor(); // Termine le traitement de la requête
        ?>
      </legend>   
      </fieldset>     
    <div class="row">
   	<br>
      <div class="col-md-2 col-md-offset-3" style="width: 330px;" >
     

       <div class="panel panel-info">
        <div class="panel-heading">
    <label class="panel-title">Ticket de caisse:</label>
    <span class ="badge" id="recaptotal"  style="float:right;">0€
    </span>
  </div>
  <div class="panel-body">
     
<form action="../moteur/vente_post.php" id="formulaire" method="post">
<ul id="liste" class="list-group">
   <li class="list-group-item">Vente: <?php echo $_GET['numero']?>#<?php echo $numero_vente?>, date: <?php echo date("d-m-Y") ?><br><?php echo $nom_pv;?><br><?php echo $adresse_pv;?>,<br>siret: <?php echo$_SESSION['siret'];?></li>
  
</ul>
 <ul class="list-group" id="total">
</ul>
<input type="text" class="form-control" placeholder="commentaire" id="comm" name="comm"><br>
<input name ="adh" id ="adh" type="checkbox" ><label for="adh">Adhére à l'association</label> <a href="adhesions.php"  target="_blank"><span style="float:right;" class="glyphicon glyphicon-pencil"></span></a>
<br>
<input type="hidden"  id="nlignes" name="nlignes">
<input type="hidden"  id="narticles" name="narticles">
<input type="hidden"  id="ptot" name="ptot">

<input type="hidden" name ="id_point_vente" id="id_point_vente" value="<?php echo $_GET['numero']?>">
    </form>
 <ul id="boutons" class="list-group">
        <button class="btn btn-default btn-lg" onclick="encaisse();">Encaisser</button>
        <button class="btn btn-default btn-lg" onclick="number_write('2');" data-value="2" align="center"><span class="glyphicon glyphicon-print"></span></button>
        <button class="btn btn-default btn-lg" onClick="javascript:window.location.reload()"><span class="glyphicon glyphicon-refresh"></button>
  </ul>


      </div>
      </div>
      </div>  
      
   
    <div class="col-md-3" style="width: 200px;">
 
      <div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"id="nom_objet"><label>Objet:</label></h3>
  </div>
  <div class="panel-body"> 
      Quantité: <input type="text" class="form-control" placeholder="Qantité" id="quantite" name="quantite" > Prix unitaire: <input type="text" class="form-control" placeholder="€" id="prix" name="prix" >
<input type="hidden"  id="id_type_objet" name="id_type_objet">
<input type="hidden"  id="id_objet" name="id_objet">   
<input type="hidden"  id="nom_objet0" name="nom_objet0">   


      <br>
     

    <button type="button" class="btn btn-default btn-lg" onclick="ajout();">
    Ajout!
    </button>

    <div class="col-md-3" style="width: 200px;">
    <div class="row">
    
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

 
<div class="col-md-2" >
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
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="margin-left:2px; margin-top:4px;">
      <span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
      </button>
      <ul class="dropdown-menu" role="menu">
      <li><a href="javascript:edite('<?php echo$donnees['nom']?>','0','<?php echo$donnees['id']?>','0')" ><?php echo$donnees['nom']?></a></li>
      <li class="divider"></li>


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
             // On recupère tout le contenu de la table grille_objets
           $req = $bdd->prepare('SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet AND visible = "oui"   ');
           $req->execute(array('id_type_dechet' => $donnees['id']));
           $i = 1;
           // On affiche chaque entree une à une
           while ($donneesint = $req->fetch())
           {
           ?>
    <li><a href="javascript:edite('<?php echo$donneesint['nom']?>','<?php echo$donneesint['prix']?>','<?php echo$donnees['id']?>','<?php echo$donneesint['id']?>')"><?php echo$donneesint['nom']?></a></li>
    </li>
           <?php }
           $req->closeCursor(); // Termine le traitement de la requête
           ?> 
    </ul>
    </div>
   
                <?php }
                $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    </div> 



    </div>
    <br><br><br>
     
    </div>
  </div>
    </div>
   
    <br>
            <?php include "pied.php" ; ?> 
<script type="text/javascript">
function markActiveLink(e) {   
    id_active = (e.id);



}
function number_write(x)
         {
          var curElement = document.activeElement;
            var text_box = document.getElementById('quantite');
            text_box.value = text_box.value + x;
          
          }
          







          function number_clear()
          {
            document.getElementById('quantite').value = "";

          }

function ajout() {
     if (isNaN((parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)) ) 
          { 
          }
          else{

if (isNaN(parseInt(document.getElementById('nlignes').value)) ) 
          { 
          document.getElementById('nlignes').value = 1;
          } 
          else
          {

          document.getElementById('nlignes').value=parseInt(document.getElementById('nlignes').value)+ 1; 
          }
if (isNaN(parseInt(document.getElementById('narticles').value)) ) 
          { 
          document.getElementById('narticles').value = document.getElementById('quantite').value;
          } 
          else
          {

          document.getElementById('narticles').value=parseInt(document.getElementById('narticles').value)+parseInt(document.getElementById('quantite').value); 
          }          

if (isNaN(parseInt(document.getElementById('ptot').value)) ) 
          { 
          document.getElementById('ptot').value = document.getElementById('prix').value*document.getElementById('quantite').value;
          } 
          else
          {

          document.getElementById('ptot').value=parseFloat(document.getElementById('ptot').value).toFixed(2)+(document.getElementById('prix').value*document.getElementById('quantite').value).toFixed(2); 
          }          

             document.getElementById('liste').innerHTML += '<li class="list-group-item"><span class="badge">'+(parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)+'€'+'</span>'
             +document.getElementById('quantite').value+'*'+document.getElementById('nom_objet0').value
             +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
             +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
             +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
             +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value+'"></li>';  
                                                
               document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
               document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+'€';

           

           

    document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
    document.getElementById('quantite').value = "";
    document.getElementById('prix').value = "";
    document.getElementById('id_type_objet').value = "";
    document.getElementById('id_objet').value = "";
    document.getElementById('nom_objet0').value = "";
    }

}

function edite(nom,prix,id_type_objet,id_objet) {
    document.getElementById('nom_objet').innerHTML = "<label>"+nom+"</label>";
    document.getElementById('quantite').value = "1";
    document.getElementById('prix').value = parseFloat(prix);
    document.getElementById('id_type_objet').value = parseFloat(id_type_objet);
    document.getElementById('id_objet').value = parseFloat(id_objet);
    document.getElementById('nom_objet0').value = nom;

}
function encaisse() {
  if (parseInt(document.getElementById('nlignes').value) >= 1) 
          { 
          document.getElementById("formulaire").submit();
          }
                    }
</script>

            <?php } else
      { 
        header('Location:../moteur/destroy.php');
      }?>

