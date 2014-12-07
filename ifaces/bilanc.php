
<?php session_start();
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi') !== false))
      { include "tete.php";?>

   <head>
      
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      
      <link href="../fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker-bs3.css" />

      <script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
      
      <script type="text/javascript" src="../js/bootstrap.min.js"></script>
      <script type="text/javascript" src="../js/moment.js"></script>
      <script type="text/javascript" src="../js/daterangepicker.js"></script>
   </head>
 

      <div class="container">
         

          
<div class"row">
  <div class="col-md-11 " >
<h1>Bilan global</h1>

   <div class="col-md-4 col-md-offset-8" >
<label for="reportrange">choisisez la periode a inspecter:</label><br>
<div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="fa fa-calendar"></i>
                  <span></span> <b class="caret"></b>
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
                    window.location.href = "bilanc.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY');
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



<script src="../js/raphael.js"></script>
      <script src="../js/morris/morris.js"></script>


            

</div>
<ul class="nav nav-tabs">
  <li ><a href="<?php echo  "bilans.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2']?>">bilan global</a></li>
  <li class="active"><a >Collectes</a></li>
  <li><a href="<?php echo  "bilanhb.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2']?>">Sorties hors boutique</a></li>
  <li><a href="<?php echo  "bilanv.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2']?>">Ventes</a></li>
  
</ul>
      
         
  </div>

      </div>    

 
  
      </div>
      



<div class="row">
   <div class="col-md-8 col-md-offset-1" >
  <h2> Bilan des collectes de la structure <?php

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
  </h2>
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
 
            // On recupère tout le contenu des visibles de la table type_dechets
            $reponse = $bdd->query('SELECT * FROM points_collecte');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "bilanc.php?numero=" . $donnees['id']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
           <li<?php if ($_GET['numero'] == 0){ echo ' class="active"';}?>><a href="<?php echo  "bilanc.php?numero=0" ."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>">Tout les points</a></li>
       </ul>

  <br>

<div class="row">
  <div class="col-md-6">        


<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Masse collectée: <?php
// on determine la masse totale collècté sur cete periode


           //on obtien la couleur de la localité dans la base





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
            // On recupère tout le contenu de la table point de vente


$req = $bdd->prepare("SELECT SUM(pesees_collectes.masse)AS total   FROM pesees_collectes  WHERE  DATE(pesees_collectes.timestamp) BETWEEN DATE(:du) AND DATE(:au)  ");
$req->execute(array('du' => $time_debut,'au' => $time_fin ));
$donnees = $req->fetch();
$mtotcolo = $donnees['total'];
echo $donnees['total']." Kgs.";
            
              $reponse->closeCursor(); // Termine le traitement de la requête
               

if ($_GET['numero'] == 0) {


  ?>
  , sur <?php
// on determine le nombre de points de collecte


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
            /*

            */
 $req = $bdd->prepare("SELECT COUNT(id) FROM points_collecte");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('au' => $time_fin ));
$donnees = $req->fetch();
     
echo $donnees['COUNT(id)'];

$req->closeCursor(); // Termine le traitement de la requête



  ?> Point(s) de collecte.

<?php } ?>
</h3>
  </div>
  <div class="panel-body">
    
<table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
    <thead>
        <tr>
            <th>Type de collecte</th>
            <th>Masse collecté</th>
            <th>%</th>
            
        </tr>
    </thead>
    <tbody>
       
        <?php
// on determine la masse totale collècté sur cete periode


     




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
 
            // On recupère tout le contenu de la table affectations







            $reponse = $bdd->prepare('SELECT 
type_collecte.nom,SUM(`pesees_collectes`.`masse`) somme,pesees_collectes.timestamp,type_collecte.id


FROM 
pesees_collectes,collectes,type_collecte

WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id

 
GROUP BY id_type_collecte');


 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           
            ?>
      
            <tr data-toggle="collapse" data-target=".parmasse<?php echo $donnees['id']?>" >
            <td><?php echo $donnees['nom'] ?></td>
            <td><?php echo $donnees['somme'] ?></td>
            <td><?php echo  round($donnees['somme']*100/$mtotcolo, 2)   ; ?></td>
            
        </tr>
      
     

 

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
 
            // On recupère tout le contenu de la table affectations







            $reponse2 = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme

 FROM type_dechets,pesees_collectes ,type_collecte , collectes

WHERE
pesees_collectes.timestamp BETWEEN :du AND :au 
AND type_dechets.id = pesees_collectes.id_type_dechet 
AND type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
AND type_collecte.id = :id_type_collecte
GROUP BY nom
ORDER BY somme DESC');
  




 $reponse2->execute(array('du' => $time_debut,'au' => $time_fin ,'id_type_collecte' => $donnees['id'] ));
           // On affiche chaque entree une à une
           while ($donnees2 = $reponse2->fetch())
           {

           
            ?>



 <tr class="collapse parmasse<?php echo $donnees['id']?> active">
    
            <td class="hiddenRow">
              <?php echo $donnees2['nom'] ?>
            </td >
            <td class="hiddenRow">
                <?php echo $donnees2['somme']." Kgs." ?>
            </td>
            <td class="hiddenRow">
                <?php echo  round($donnees2['somme']*100/$donnees['somme'], 2)." %"  ; ?>
            </td>
            
        









        </tr>
 <?php

             }
              $reponse2->closeCursor(); // Termine le traitement de la requête
                ?>

        



      <?php

             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>

      
    </tbody>
</table>




<br>


          <p><div id="graphmasse" style="height: 180px;"></div></p>


  </div>
</div>


  </div>
  <div class="col-md-6">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Nombre de collectes: <?php
// on determine la masse totale collècté sur cete periode


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
            /*
SELECT SUM(masse),timestamp FROM pesees_collectes WHERE  `timestamp`BETWEEN '2014-09-18 00:00:00' AND '2014-09-24 23:59:59'
            */
 $req = $bdd->prepare("SELECT SUM(pesees_collectes.masse),pesees_collectes.timestamp  FROM pesees_collectes  WHERE  `pesees_collectes.timestamp` BETWEEN :du AND :au  ");
$req->execute(array('du' => $time_debut,'au' => $time_fin ));
$donnees = $req->fetch();
     
echo $donnees['SUM(pesees_collectes.masse)'].' Kgs.';

$req->closeCursor(); // Termine le traitement de la requête



if ($_GET['numero'] == 0) {
  ?>





  , sur <?php
// on determine le nombre de points de collecte


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
            /*

            */
 $req = $bdd->prepare("SELECT COUNT(id) FROM points_collecte WHERE  `timestamp` < :au ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('au' => $time_fin ));
$donnees = $req->fetch();
     
echo $donnees['COUNT(id)'];

$req->closeCursor(); // Termine le traitement de la requête



  ?> Point(s) de collecte.

<?php } ?>


</h3>
  </div>
  <div class="panel-body">
    
<table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
    <thead>
        <tr>
            <th>Type de collecte</th>
            <th>Nombre de collectes</th>
            <th>%</th>
            
        </tr>
    </thead>
    <tbody>
        <tr data-toggle="collapse" data-target=".demo1" class="active">
            <td>a dom</td>
            <td>125</td>
            <td>58</td>
            
        </tr>
      
        
        <tr class="collapse demo1">
            <td class="hiddenRow">
               deee
            </td >
            <td class="hiddenRow">
                100kgs
            </td>
            <td class="hiddenRow">
                75%
            </td>
            
        </tr>


        <tr class="collapse demo1">
            <td class="hiddenRow">
                textile
            </td >
            <td class="hiddenRow">
                25kgs
            </td>
            <td class="hiddenRow">
                25%
            </td>
            
        </tr>
 <tr data-toggle="collapse" data-target=".demo2" class="active">
            <td>apport vol.</td>
            <td>125</td>
            <td>58</td>
            
        </tr>
      
        
        <tr class="collapse demo2">
            <td class="hiddenRow">
               mobilier
            </td>
            <td class="hiddenRow">
                100kgs
            </td>
            <td class="hiddenRow">
                75%
            </td>
            
        </tr>


        <tr class="collapse demo2">
            <td class="hiddenRow">
                autres
            </td>
            <td class="hiddenRow">
                25kgs
            </td>
            <td class="hiddenRow">
                25%
            </td>
            
        </tr>

      
    </tbody>
</table>

<script>       Morris.Donut({
    element: 'graphmasse',
    data: [
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
 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme FROM type_dechets,vendus WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE()
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "{value:".$donnees['somme'].", label:'".$donnees['nom']."'},";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
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
 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme FROM type_dechets,vendus WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE()
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "'".$donnees['couleur']."'".",";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " pcs."}
    });
</script>




  </div>
</div>
  </div>
  
</div>

<br>
 

       
</div>
        </div>

 







   


<?php include "pied_bilan.php";?>
<?php }
    else
    header('Location: ../moteur/destroy.php') ;
?>
