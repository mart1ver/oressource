
<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page:
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
                    window.location.href = "bilans.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY');
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

            

</div>
<ul class="nav nav-tabs">
  <li ><a href="<?php echo  "bilans.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2']?>">bilan global</a></li>
  <li><a href="<?php echo  "bilanc.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2']?>" >Collectes</a></li>
  <li><a href="<?php echo  "bilanhb.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2']?>">Sorties hors boutique</a></li>
  <li class="active"><a>Ventes</a></li>
  
</ul>
      
         
  </div>

      </div>    

 
  
      </div>
      <hr />



<div class="row">
   <div class="col-md-8 col-md-offset-1" >
  <span class="label label-success"> Survol global de la structure <?php

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
  </span>
  <br>



<br>
 


         



<span class="label label-info">

          Nombre de ventes:
           <?php
// on determine le nombre de ventes sur cete periode au total


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
 $req = $bdd->prepare("SELECT COUNT(id),timestamp FROM ventes WHERE  `timestamp` BETWEEN :du AND :au ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('du' => $time_debut,'au' => $time_fin ));
$donnees = $req->fetch();
     
echo $donnees['COUNT(id)'];

$req->closeCursor(); // Termine le traitement de la requête



  ?> , sur <?php
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
SELECT SUM(masse),timestamp FROM pesees_collectes WHERE  `timestamp`BETWEEN '2014-09-18 00:00:00' AND '2014-09-24 23:59:59'
            */
 $req = $bdd->prepare("SELECT COUNT(id) FROM points_vente WHERE  `timestamp` < :au ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('au' => $time_fin ));
$donnees = $req->fetch();
     
echo $donnees['COUNT(id)'];

$req->closeCursor(); // Termine le traitement de la requête



  ?> point(s) de vente.</span>
<br>

 repartition par type 

       
</div>
        </div>

 




recap total des ventes pour la boutique 1 !
quantite dELECTROMENAGER /INFORMATIQUE, totale vendue:  3740 
chiffre degage :  20336.98
prix moyen: 5.4376951871658 
quantite de MOBILIER totale vendue: 3057
chiffre degage :  24489.55
prix moyen: 8.0109748119071
quantite de TEXTILES /ACCESSOIRES/BIJOUX totale vendue: 68335
chiffre degage :  130985.850
prix moyen: 1.9168193458696
quantite de VAISSELLE totale vendue:  24332
chiffre degage :  25540.21
prix moyen: 1.0496551865856
quantite de LIVRES totale vendue: 35906
chiffre degage :  24754.11
prix moyen: 0.68941430401604
quantite de SUPPORTS MEDIA totale vendue: 13079
chiffre degage :  8360.57
prix moyen: 0.6392361801361
quantite de JOUETS totale vendue: 8414
chiffre degage :  14696.63
prix moyen: 1.7466876634181
quantite de BIBELOTS/QUINCAILLERIE totale vendue: 19777
chiffre degage :  37166.85
prix moyen: 1.8792966577337
quantite dobjets inclassables totale vendue:  2024
chiffre degage :  5283.30
prix moyen: 2.6103260869565 

nombre total dobjets vendus:  178664 
chiffre total degage: 291614.05 
prix moyen total dun objet: 1.6321925513814 
nombre total de ventes: 54665 
nombre dobjet par vente en moyenne:
3.2683435470594 
prix moyen dun panier:
5.3345659928656 

<?php include "pied_bilan.php"; 
}
    else
    {header('Location: ../moteur/destroy.php') ;}
?>
