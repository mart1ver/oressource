<?php session_start();?>
<head>
      
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      
      
      <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker-bs3.css" />

      <script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
      
      <script type="text/javascript" src="../js/bootstrap.min.js"></script>
      <script type="text/javascript" src="../js/moment.js"></script>
      <script type="text/javascript" src="../js/daterangepicker.js"></script>
   </head>

<?php

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" 
//formulaire permettant la correction de sorties
?>


   <div class="container">
        <h1>Vérification des sorties hors boutique</h1> 
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
<ul class="nav nav-tabs">


 <?php 
          //on affiche un onglet par type d'objet
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
 
            // On recupère tout le contenu des visibles de la table points_sortie
            $reponse = $bdd->query('SELECT * FROM points_sortie');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "verif_sorties.php?numero=" . $donnees['id']."&date=" . $_GET['date']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
       </ul>



<br>



   <div class="row">
  <div class="col-md-3 col-md-offset-9" >
  <label for="reportrange">Choisisez la periode a inspecter:</label><br>
<div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="fa fa-calendar"></i>
                  <span></span> <b class="caret"></b>
               </div>
</div>


               <script type="text/javascript">
               $(document).ready(function() {

                  var cb = function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                    $('#reportrange span').html(start.format('DD, MMMM, YYYY') + ' - ' + end.format('DD, MMMM, YYYY'));
                    //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
                  }

                  var optionSet1 = {
                    startDate: moment(),
                    endDate: moment(),
                    minDate: '01/01/2010',
                    maxDate: '12/31/2020',
                    dateLimit: { days: 60 },
                    showDropdowns: true,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: true,
                    ranges: {
                       "Aujoud'hui": [moment(), moment()],
                       'hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                       '7 derniers jours': [moment().subtract(6, 'days'), moment()],
                       '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                       'Ce mois': [moment().startOf('month'), moment().endOf('month')],
                       'Le mois deriner': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    opens: 'left',
                    buttonClasses: ['btn btn-default'],
                    applyClass: 'btn-small btn-primary',
                    cancelClass: 'btn-small',
                    format: 'DD/MM/YYYY',
                    separator: ' to ',
                    locale: {
                        applyLabel: 'Appliquer',
                        cancelLabel: 'Anuler',
                        fromLabel: 'Du',
                        toLabel: 'Au',
                        customRangeLabel: 'Période libre',
                        daysOfWeek: ['Di','Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                        monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
                        firstDay: 1
                    }
                  };

                  

                  $('#reportrange span').html(moment().format('D, MMMM, YYYY') + ' - ' + moment().format('D, MMMM, YYYY'));

                  $('#reportrange').daterangepicker(optionSet1, cb);

                  $('#reportrange').on('show.daterangepicker', function() { console.log("show event fired"); });
                  $('#reportrange').on('hide.daterangepicker', function() { console.log("hide event fired"); });
                  $('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
                    console.log("apply event fired, start/end dates are " 
                      + picker.startDate.format('DD MM, YYYY') 
                      + " to " 
                      + picker.endDate.format('DD MM, YYYY')                      
                    ); 
                    window.location.href = "verif_sorties.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY')+"&numero="+"<?php echo $_GET['numero']?>";
                  });
                  $('#reportrange').on('cancel.daterangepicker', function(ev, picker) { console.log("cancel event fired"); });

                  $('#options1').click(function() {
                    $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
                  });

                  $('#options2').click(function() {
                    $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
                  });

                  $('#destroy').click(function() {
                    $('#reportrange').data('daterangepicker').remove();
                  });

               });
               </script>
          

<?php
// on affiche la periode visée
  if($_GET['date1'] == $_GET['date2']){
    echo' le '.$_GET['date1'];

  }
  else
  {
  echo' du '.$_GET['date1']." au ".$_GET['date2']." :";  
}
//on convertit les deux dates en un format compatible avec la bdd

$txt1  = $_GET['date1'];
$date1ft = DateTime::createFromFormat('d-m-Y', $txt1);
$time_debut = $date1ft->format('Y-m-d');
$time_debut = $time_debut." 00:00:00";

$txt2  = $_GET['date2'];
$date2ft = DateTime::createFromFormat('d-m-Y', $txt2);
$time_fin = $date2ft->format('Y-m-d');
$time_fin = $time_fin." 23:59:59";



  ?>
   </div>


</div>
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


 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au  AND classe = "sorties" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
            <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Dons simples:</h3> </div>
  <div class="panel-body">
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
            <th>type de collecte:</th>
            <th>Adhérent?:</th>
            
            <th>Masse totale</th>
            
            <th>Modifier:</th>
            
          </tr>
        </thead>
        <tbody>
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
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,type_sortie.nom,sorties.adherent ,sorties.classe classe 
                       FROM sorties ,type_sortie
                       WHERE type_sortie.id = sorties.id_type_sortie  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
            <td><?php echo $donnees['adherent']?></td>
            
           <td> 

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
 
           
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sorties.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
       
        
      </table>
      </div>
</div>
      <?php
    } else{echo 'Pas de dons sur cette periode<br><br>';
    $req->closeCursor(); }
}
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


 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesc" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
            <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Sorties conventionées:</h3> </div>
  <div class="panel-body">

  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
            <th>Nom du partenaire:</th>
            
            <th>Masse totale</th>
            
            <th>Modifier:</th>
            
          </tr>
        </thead>
        <tbody>
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
 
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,conventions_sorties.nom,sorties.adherent , sorties.classe classe
                       FROM sorties ,conventions_sorties
                       WHERE conventions_sorties.id = sorties.id_convention  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesc" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
            
           <td> 

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
 
           
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sortiesc.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
        
        
      </table>
       </div>
</div>
      <?php
    } else{echo 'Pas de sorties en direction des partenaires sur cette periode<br><br>';
    $req->closeCursor(); }
}
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


 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie  AND DATE(sorties.timestamp) BETWEEN :du AND :au   AND classe = "sortiesr" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
             <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Sorties recyclage:</h3> </div>
  <div class="panel-body">

  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
            <th>Nom de l'entreprise:</th>
            <th>Masse totale</th>
            
            <th>Modifier:</th>
            
          </tr>
        </thead>
        <tbody>
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
 
           
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,filieres_sortie.nom , sorties.classe classe
                       FROM sorties ,filieres_sortie
                       WHERE filieres_sortie.id = sorties.id_filiere  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesr"');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
           
           <td> 

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
 
            
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sortiesr.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
        
        
      </table>
      </div></div>
      <?php
    } else{echo 'Pas de sorties recyclage sur cette periode<br><br>';
    $req->closeCursor(); }
}
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


 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesp" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
            <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Sorties Poubelles:</h3> </div>
  <div class="panel-body">
 
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
   
            
            <th>Masse totale</th>
            
            <th>Modifier:</th>
            
          </tr>
        </thead>
        <tbody>
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
 
            
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp , sorties.classe classe
                       FROM sorties 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesp" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            
            
           <td> 

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
 
           
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sortiesp.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
        
        
      </table>
    </div></div>
<?php
    } else{echo 'Pas de poubelles evacuées sur cette periode<br><br>';
    $req->closeCursor(); }
}
?>



  </div><!-- /.container -->
<?php include "pied_bilan.php";
 }
    else
{
   header('Location: ../moteur/destroy.php') ;
}
?>
       
      
