<?php session_start(); 

require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'v'.$_GET['numero']) !== false))
      {include "tete_vente.php";?>



<div class="panel-body">

      <fieldset>
      <legend>
        <?php 
          // on determine le numero de la vente
         $req = $bdd->prepare("SELECT max(id) FROM ventes WHERE id_point_vente = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entrée une à une
           while ($donnees = $req->fetch())
           {
            $numero_vente = $donnees['max(id)'] + 1;
           }
              $req->closeCursor(); // Termine le traitement de la requête
            //on affiche le nom du point de vente
            // On recupère tout le contenu de la table point de collecte
          
            $req = $bdd->prepare("SELECT * FROM points_vente WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entrée une à une
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
      <div class="col-md-2 col-md-offset-2" style="width: 330px;" >
     

       <div class="panel panel-info">
        <div class="panel-heading">
    <label class="panel-title">Ticket de caisse:</label>
    <span class ="badge" id="recaptotal"  style="float:right;">0€
    </span>
  </div>
  <div class="panel-body" id="divID">
     <form action="../moteur/vente_post.php" id="formulaire" method="post">

<?php if ($_SESSION['saisiec'] == 'oui' AND (strpos($_SESSION['niveau'], 'e') !== false) ){ ?>
      Date de la vente:  <input type="date" id="antidate" name="antidate" style="height:20px;" value=<?php echo date("Y-m-d") ?>>
<br>
<br>
<?php }?>

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
  <b id="labellot">vente à:  </b>
<input type="checkbox" name="my-checkbox"   checked  data-on-text="l'unité" data-off-text="lot" data-handle-width="45" data-size="small" >
<p>
<?php }?>
<script type="text/javascript">
$("[name='my-checkbox']").bootstrapSwitch();
$('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
//console.log(state); // true | false
  switchlot(state); // true | false
});
</script>






<b>Quantité:</b> <input type="text" class="form-control" placeholder="Quantité" id="quantite" name="quantite" onfocus="fokus(this)" > <b id = "labelpul">Prix unitaire:</b> <input type="text" class="form-control" placeholder="€" id="prix" name="prix" onfocus="fokus(this)">
<input type="hidden"  id="id_type_objet" name="id_type_objet">
<input type="hidden"  id="id_objet" name="id_objet">   
<input type="hidden"  id="nom_objet0" name="nom_objet0">   
<input type="hidden"  id="sul" name="sul" value ="unite" >   


   
<br>
    <button type="button" class="btn btn-default btn-lg" onclick="ajout();">
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



      <div class="panel panel-info">
        <div class="panel-heading">
    <h2 class="panel-title"id="rendu">Rendu monaie:</h2>
  </div>
  <div class="panel-body" id="panelrendu"> 
   
    


<ul class="list-group">
  <li class="list-group-item">

Somme due:
  <input type="text" class="form-control" placeholder="€" aria-describedby="sizing-addon3" name="rendua" id="rendua" disabled>


  </li>
  <li class="list-group-item">

Réglement
  <input type="text" class="form-control" placeholder="€" aria-describedby="sizing-addon3" name="rendub" id="rendub" onfocus="fokus(this)" oninput="rendu()">


  </li>
  <li class="list-group-item">
    
A rendre
  <input type="text" class="form-control" placeholder="€" aria-describedby="sizing-addon3" name="renduc" id="renduc" disabled>


  </li>
</ul>


    
    



   
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
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?>
      <div class="btn-group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="margin-left:8px; margin-top:16px;">
      <span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
      </button>
      <ul class="dropdown-menu" role="menu">
      <li style="font-size:18px"><a href="javascript:edite('<?php echo$donnees['nom']?>','0','<?php echo$donnees['id']?>','0')" ><?php echo$donnees['nom']?></a></li>
      <li class="divider"></li>


  <?php 
             // On recupère tout le contenu de la table grille_objets
           $req = $bdd->prepare('SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet AND visible = "oui"  ORDER BY nom ');
           $req->execute(array('id_type_dechet' => $donnees['id']));
           $i = 1;
           // On affiche chaque entree une à une
           while ($donneesint = $req->fetch())
           {
           ?>
    <li style="font-size:18px"><a href="javascript:edite('<?php echo$donneesint['nom']?>','<?php echo$donneesint['prix']?>','<?php echo$donnees['id']?>','<?php echo$donneesint['id']?>')"><?php echo$donneesint['nom']?></a></li>
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
    <br>
    <div class="panel panel-info">
       
  <div class="panel-body"> 
<label>Moyen de paiement:</label>
<br>




<div class="btn-group" data-toggle="buttons">
<?php

// On produit du style à la volée pour les boutons de paiement
// Si le bouton est inactif on le rend transparent

?>
<style type="text/css">
.ors_btn_pay {
    opacity: 0.5;
    color: #dddddd;	
    font-weight: bold;
}

.ors_btn_pay.active, .ors_btn_pay:active {
    opacity: 1.0;
    color: #dddddd;
    font-weight: bold;
}
</style>

 <?php 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT id,nom,couleur FROM moyens_paiement WHERE visible = "oui"');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
		$id=$donnees['id'];
		if ($id==1) {
			$active="active";
			$checked="checked";
		}else{
			$active ="";
			$checked="";
		}
		$nom=$donnees['nom'];
		$couleur=$donnees['couleur'];
           	print "<label class='btn ors_btn_pay  btn-default $active' ";
		print "onclick=\"moyens('$id');\" ";
		print "style='background-color:$couleur;' ";
		print ">";
		print "<input type='radio' name='paiement' id='paiement' autocomplete='off' value='$id' $checked >";
		print "$nom";
		print "</label>";

	   }
           
           $reponse->closeCursor(); // Termine le traitement de la requête
?>








  
  


</div>






<br><br>


 <input type="text" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire">

</div>
</div>
<br>
<ul id="boutons" class="list-group">
        <button class="btn btn-danger btn-lg" onclick="encaisse();" style="height:70px">Encaisser</button>
        <button class="btn btn-danger btn-lg" type="button"   align="center" onclick="printdiv('divID');" value=" Print "><span class="glyphicon glyphicon-print"></span></button>
        <button class="btn btn-warning btn-lg" onclick="javascript:window.location.reload()"><span class="glyphicon glyphicon-refresh"></button>
  
<br><br>
    

<?php /*
    <a href="remboursement.php?numero=<?php echo $_GET['numero']?>&nom=<?php echo $_GET['nom']?>&adresse=<?php echo $_GET['adresse']?>"> 
    */ ?>
    <button type="button"  class="btn btn-danger pull-right" onclick="rembou();" >
    Remboursement
    </button>
   



    </ul>

    
     
    </div>
  </div>
    </div>

<?php if ($_SESSION['viz_caisse'] == 'oui'){ ?>
     <div class="col-md-2 col-md-offset-2" style="width: 330px;" >


  <a href="viz_caisse.php?numero=<?php echo $_GET['numero'] ?>" target="_blank">visualiser les <?php echo $_SESSION['nb_viz_caisse'] ?> dernieres ventes</a>


 </div>
<?php }?>
 <br><br><br>   
            <?php include "pied.php" ; ?> 
<script type="text/javascript">

function rendu() 

{
document.getElementById('renduc').value = document.getElementById('rendub').value -  document.getElementById('rendua').value;
}

function rembou() {
  var code_soumis = prompt('Veuillez renseigner le code de remboursement');
var codi = <?php 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT cr FROM `description_structure`');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
          echo $donnees['cr'];
}
                $reponse->closeCursor(); // Termine le traitement de la requête
                ?>;
 if (code_soumis == codi) {
         window.location = "remboursement.php?numero=<?php echo $_GET['numero']?>&nom=<?php echo $_GET['nom']?>&adresse=<?php echo $_GET['adresse']?>";
       }else{
       alert("Code de remboursement non valide!");
     }
}
var what;
function fokus(that) {
what = that;
}
function moyens(moy) {
 document.getElementById('moyen').value = moy;
}
function often(that) {
  if (document.getElementById('ptot').value > 0 && isNaN(parseInt(document.getElementById('id_type_objet').value))){what.value = what.value + that.value;
document.getElementById('quantite').value ="";
document.getElementById('prix').value ="";
if (that.value == "c"){what.value = "";}
document.getElementById('renduc').value = document.getElementById('rendub').value -  document.getElementById('rendua').value;
  }
if (isNaN(parseInt(document.getElementById('id_type_objet').value)) ) 
          { 
            
          }
          else{
if (that == null) {document.getElementById('quantite').value ="" ; what = document.getElementById('quantite');}
if (that.value == "c"){what.value = "";}
else{
what.value = what.value + that.value;
}
}
}
function printdiv(divID)
    {
if (parseInt(document.getElementById('nlignes').value) >= 1) 
          { 
            
      
      var headstr = "<html><head><title></title></head><body><small>";
      
 <?php if ($_SESSION['tva_active'] == 'oui'){?>
  var prixtot =  parseFloat(document.getElementById('ptot').value).toFixed(2);
  var prixht = parseFloat(prixtot).toFixed(2) / ( 1+parseFloat(<?php echo $_SESSION['taux_tva'] ?>).toFixed(2)/100 );
  var ptva = parseFloat(prixtot).toFixed(2)-parseFloat(prixht).toFixed(2)
var footstr = "TVA à <?php echo $_SESSION['taux_tva'] ?>%"+" Prix H.T. ="+parseFloat(prixht).toFixed(2)+"€ TVA="+parseFloat(ptva).toFixed(2)+"€";
<?php
  }else{?>
var footstr = "Association non assujettie à la TVA.</body></small> ";
  <?php } ?>
      var newstr = document.all.item(divID).innerHTML;
      var oldstr = document.body.innerHTML;
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      //return false;

          }

          //puis encaisse
           if ((parseInt(document.getElementById('nlignes').value) >= 1) && ((document.getElementById('quantite').value == "")||(document.getElementById('quantite').value == "0"))&&((document.getElementById('prix').value == "")||(document.getElementById('prix').value == "0")) )
          { 
            document.getElementById('comm').value = document.getElementById('commentaire').value
            
          document.getElementById("formulaire").submit();
          }
    }


function suprime(nsligne)
{
if (parseInt(document.getElementById('nlignes').value)>1)
{
var numero_ligne = nsligne.substr(5); // sous_chaine = le numero uniquement
document.getElementById('narticles').value = parseInt(document.getElementById('narticles').value) - parseInt(document.getElementById('tquantite'+numero_ligne).value) ;
document.getElementById('ptot').value = parseFloat(document.getElementById('ptot').value) - (parseFloat(document.getElementById('tprix'+numero_ligne).value)*parseFloat(document.getElementById('tquantite'+numero_ligne).value))  ;
document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+'€';
document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
document.getElementById('tquantite'+numero_ligne).value= "0";
document.getElementById('tprix'+numero_ligne).value= "0";
//document.getElementById('nlignes').value = parseInt(document.getElementById('nlignes').value) - 1 ;
document.getElementById(nsligne).remove();
document.getElementById('rendua').value = document.getElementById('ptot').value ;
}
else
{
window.location.reload();
}
}



function switchlot(state) {

  if (state == false){
 
document.getElementById('sul').value = "lot";
document.getElementById('labellot').innerHTML = "vente au: ";
document.getElementById('labelpul').innerHTML = "Prix du lot: ";
document.getElementById('panelcalc').style.backgroundColor = '#A18681';
}
else
{
 document.getElementById('sul').value = "unite";
document.getElementById('labellot').innerHTML = "vente à:  "  ;
document.getElementById('labelpul').innerHTML = "Prix unitaire: ";
document.getElementById('panelcalc').style.backgroundColor = 'white';
}
}



function ajout() {


if ( document.getElementById('sul').value == "unite"){
  var prixtemp;
  prixtemp = document.getElementById('prix').value;
   prixtemp = prixtemp.replace(",", ".");
   document.getElementById('prix').value = prixtemp;
     if (isNaN((parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)) ) 
         {} 
          else
          {
         
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
          document.getElementById('ptot').value=parseFloat(document.getElementById('ptot').value)+parseFloat(document.getElementById('prix').value*document.getElementById('quantite').value); 
          document.getElementById('rendua').value = document.getElementById('ptot').value 
          }          
             document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
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
      else {


 var prixtemp;
  prixtemp = document.getElementById('prix').value;
   prixtemp = prixtemp.replace(",", ".");
   document.getElementById('prix').value = prixtemp;
     if (isNaN((parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)) ) 
         {} 
          else
          {
         
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
          document.getElementById('ptot').value = document.getElementById('prix').value;
          document.getElementById('rendua').value = document.getElementById('ptot').value ;
          } 
          else
          {
          document.getElementById('ptot').value=parseFloat(document.getElementById('ptot').value)+parseFloat(document.getElementById('prix').value);
          }          
             document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(document.getElementById('prix').value).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
             +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
             +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
             +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
             +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value/document.getElementById('quantite').value+'"></li>';                                               
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
      document.getElementById('rendua').value = document.getElementById('ptot').value ;
      document.getElementById('renduc').value = document.getElementById('rendub').value -  document.getElementById('rendua').value;

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
  if ((parseInt(document.getElementById('nlignes').value) >= 1) && ((document.getElementById('quantite').value == "")||(document.getElementById('quantite').value == "0"))&&((document.getElementById('prix').value == "")||(document.getElementById('prix').value == "0")) )
          { 
            document.getElementById('comm').value = document.getElementById('commentaire').value
            
          document.getElementById("formulaire").submit();
          }
                    }
</script>

            <?php 
} 
else
      { 
        header('Location:../moteur/destroy.php');
      }
?>
