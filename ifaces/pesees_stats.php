<?php session_start(); 

require_once '../moteur/dbconfig.php' ;


//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'v'.$numero) !== false)) {
  include "tete_vente.php";
  
  // on détermine la référence de la prochaine vente.
  $req = $bdd->query("SHOW TABLE STATUS where name='ventes'");
  $donnees = $req->fetch();
  $req->closeCursor(); // Libère la connexion au serveur
  $numero_vente = $donnees['Auto_increment'];

 
?>

<div class="panel-body">
  <fieldset>
    <legend>Pesées pours Statistiques</legend> 
  </fieldset>     

  <div class="row">
    <br>
    <div class="col-md-2 col-md-offset-2" style="width: 330px;" >
      <div class="panel panel-info">

        <div class="panel-heading">
          <label class="panel-title">Bon de pesée:</label>
          <span class ="badge" id="recaptotal" style="float:right;">0€</span>
        </div>

        <div class="panel-body" id="divID">
          <form action="../moteur/vente_post.php" id="formulaire" method="post">



<ul id="liste" class="list-group">
   <li class="list-group-item">Vente: <?php echo $_GET['numero']?>#<?php echo $numero_vente?>, date: <?php echo date("d-m-Y") ?><br><?php echo $nom_pv;?><br><?php echo $adresse_pv;?>,<br>siret: <?php echo$_SESSION['siret'];?></li>
  
</ul>
 <ul class="list-group" id="total">
</ul>
<input type="hidden" id="comm" name="comm"><br>
<input type="hidden" id="moyen" name="moyen" value="1"><br>
<input type="hidden"  id="nlignes" name="nlignes">
<input type="hidden"  id="narticles" name="narticles">
<input type="hidden"  id="ptot" name="ptot">
<input type="hidden" id="id_user" name="id_user" value=<?php echo'"'.$_SESSION['id'].'"' ?>  >
<input type="hidden" id="saisiec_user" name="saisiec_user" value=<?php echo'"'.$_SESSION['saisiec'].'"' ?>  >
<input type="hidden" id="niveau_user" name="niveau_user" value=<?php echo'"'.$_SESSION['niveau'].'"' ?>  >
<input type="hidden" name ="id_point_vente" id="id_point_vente" value="<?php echo $_GET['numero']?>">
    </form>
 

      </div>
      </div>
      </div>  
      
   
    <div class="col-md-3" style="width: 220px;">
 
      <div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"id="nom_objet"><label>Objet:</label></h3>
  </div>
  <div class="panel-body" id="panelcalc"> 
     


     <?php if ($_SESSION['lot_caisse'] == 'oui'){ ?>
<p align="right">
  <b id="labellot">Pesée à:  </b>
<input type="checkbox" name="my-checkbox"   checked  data-on-text="l'unité" data-off-text="lot" data-handle-width="45" data-size="small" >
<p>
<?php }?>
<script type="text/javascript">
"use strict";
$("[name='my-checkbox']").bootstrapSwitch();
$('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
//console.log(state); // true | false
  switchlot_stats(state); // true | false
});
</script>






<b>Quantité:</b>
<input type="text" class="form-control" placeholder="Quantité" id="quantite" name="quantite" onfocus="fokus(this)" > 
<b id = "labelmasse">Masse unitaire:</b> 
<input type="text" class="form-control" placeholder="Kgs." id="masse" name="masse" onfocus="fokus(this)">
<input type="hidden"  id="id_type_objet" name="id_type_objet">
<input type="hidden"  id="id_objet" name="id_objet">   
<input type="hidden"  id="nom_objet0" name="nom_objet0">   
<input type="hidden"  id="sul" name="sul" value ="unite" >   


   
<br>
    <button type="button" class="btn btn-default btn-lg" onclick="ajout_stats();">
    Ajouter
    </button>


    <div class="col-md-3" style="width: 200px;">
    <div class="row">
    
    </div>
   
    <div class="row">
        <button class="btn btn-default btn-lg" value="1" onclick="often(this);" style="margin-top:8px;">1</button>
        <button class="btn btn-default btn-lg" value="2" onclick="often(this);" style="margin-left:8px; margin-top:8px;">2</button>
        <button class="btn btn-default btn-lg" value="3" onclick="often(this);" style="margin-left:8px; margin-top:8px;">3</button>
    </div>
    <div class="row">
        <button class="btn btn-default btn-lg" value="4" onclick="often(this);" style="margin-top:8px;">4</button>
        <button class="btn btn-default btn-lg" value="5" onclick="often(this);" style="margin-left:8px; margin-top:8px;">5</button>
        <button class="btn btn-default btn-lg" value="6" onclick="often(this);" style="margin-left:8px; margin-top:8px;">6</button>
    </div>
    <div class="row">
        <button class="btn btn-default btn-lg" value="7" onclick="often(this);" style="margin-top:8px;">7</button>
        <button class="btn btn-default btn-lg" value="8" onclick="often(this);" style="margin-left:8px; margin-top:8px;">8</button>
        <button class="btn btn-default btn-lg" value="9" onclick="often(this);" style="margin-left:8px; margin-top:8px;">9</button>
    </div>
    <div class="row">
        <button class="btn btn-default btn-lg" value="c" onclick="often(this);" style="margin-top:8px;">C</button>
        <button class="btn btn-default btn-lg" value="0" onclick="often(this);" style="margin-left:8px; margin-top:8px;">0</button>
        <button class="btn btn-default btn-lg" value="." onclick="often(this);" style="margin-left:8px; margin-top:8px;">,</button>
    </div>
    </div>


  </div>
  </div>



  






 


    </div>

 
<div class="col-md-3" >
<div class="panel panel-info">
        <div class="panel-heading">
    <h3 class="panel-title"><label>Type d'objet:</label></h3>
  </div>
  <div class="panel-body"> 
      
<?php 

//
// Affichage des différents Types de déchets ( "Types d'objet" )
// avec les tarifs pré-définis (s'il y en a)
//

// On recupère tout les types des déchets "actifs"
$dechets = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
 
// On affiche un bouton pour chaque type de déchet 
while ($d = $dechets->fetch())
{
      	$couleur_dechet=$d['couleur'];
       	$id_dechet=$d['id'];
       	$nom_dechet=$d['nom'];
	$action_dechet="javascript:edite_stats('$nom_dechet','0','$id_dechet','0')";

	print "<div class='btn-group'>";
		
	// On récupère la grille de tarifs pour ce type de déchets
        $tarifs = $bdd->prepare('SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet AND visible = "oui"  ORDER BY nom ');
        $tarifs->execute(array('id_type_dechet' => $id_dechet));

	// S'il n'y pas de tarif pour ce type de déchets
	// Alors on affiche un bouton tout simple 	
	if ($tarifs->rowCount() == 0 )
	{
		print "<button type='button' class='btn btn-default' style='margin-left:8px; margin-top:16px;'>";
		print "<span class='badge' id='cool' style='background-color:$couleur_dechet'>";
		print "<a href=\"$action_dechet\" style='color:#ffffff;'>$nom_dechet</a>";
		print "</span>";
      		print "</button>";
	} else {

	// S'il y a une grille de tarif pour ce type 
	// Alors on affiche un menu déroulant		 

		// Le premier item du menu déroulant c'est le type de déchet lui-même (avec un prix pré-défini à 0)
		print "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' style='margin-left:8px; margin-top:16px;'>";
      		print "<span class='badge' id='cool' style='background-color:$couleur_dechet'>$nom_dechet</span>";
      		print "</button>";
      		print "<ul class='dropdown-menu' role='menu'>";
      		print "<li style='font-size:18px'>";
		print "<a href=\"$action_dechet\" >$nom_dechet</a>";
		print "</li>";
			
		// un séparateur
      		print "<li class='divider'></li>"; 
     
		// Ensuite on récupère chaque ligne de la grille de tarif
		// et on ajoute un item dans le menu déroulant
                while ($t = $tarifs->fetch())
           	{
			$id_tarif=$t['id'];
			$prix_tarif=$t['prix'];
			$nom_tarif=$t['nom'];
			$action_tarif="javascript:edite('$nom_tarif','$prix_tarif','$id_dechet',$id_tarif)";

			print "<li style='font-size:18px'>";
			print "<a href=\"$action_tarif\">$nom_tarif</a>";
			print "</li>";
       		}

		// Fin du menu déroulant
		print "</ul>";
   	}
           	
	// destruction de la grille de tarif
	$tarifs->closeCursor(); 
    
	// Fin du groupe de boutons
	print "</div>";

}   

// destruction de la liste des déchets
$dechets->closeCursor(); 

// Fin du Paneau Type d'objet        
?>
</div> 



    </div>
    <br>
    <div class="panel panel-info">
       
  <div class="panel-body"> 
<label>Commentaire:</label>
<br>


 <input type="text" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire">

</div>
</div>
<br>
<ul id="boutons" class="list-group">
        <button class="btn btn-danger btn-lg" onclick="encaisse_stats();" style="height:70px">C'est pesé</button>
        <button class="btn btn-warning btn-lg" onclick="javascript:window.location.reload()"><span class="glyphicon glyphicon-refresh"></button>
  
<br><br>
    



    </ul>

    
     
    </div>
  </div>
    </div>


 <br><br><br>   
            <?php include "pied.php" ; ?> 
<script>
"use strict";
var force_pes_vente = "oui";
</script>
<script src="../js/ventes.js"></script>


            <?php 
} 
else
      { 
        header('Location:../moteur/destroy.php');
      }
?>
