<?php session_start();

require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
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






 <script src="../js/raphael.js"></script>
 <script src="../js/morris/morris.min.js"></script>



   </head>
  
  <div class="container">
  <div class="row">
  <div class="col-md-11 " >
   <h1>Bilan global</h1>
    <div class="col-md-4 col-md-offset-8" >
     <label for="reportrange">Choisissez la période à inspecter:</label><br>

      <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
       <i class="fa fa-calendar"></i>
       <span></span> <b class="caret"></b>
      </div>
     <script type="text/javascript">
"use strict";
function $_GET(param) {
  var vars = {};
  window.location.href.replace( 
    /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
    function( m, key, value ) { // callback
      vars[key] = value !== undefined ? value : '';
    }
  );

  if ( param ) {
    return vars[param] ? vars[param] : null;  
  }
  return vars;
}
                   $(document).ready(function() {

                  var cb = function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                    $('#reportrange span').html(start.format('DD, MMMM, YYYY') + ' - ' + end.format('DD, MMMM, YYYY'));
                   // alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
                  }
var dateuno = $_GET('date1');
var moisuno = dateuno.substring(0,2);
var jouruno = dateuno.substring(3,5);
var anneeuno = dateuno.substring(6,10);
var dateunogf = moisuno+'/'+jouruno+"/"+anneeuno;


var datedos = $_GET('date2');
var moisdos = datedos.substring(0,2);
var jourdos = datedos.substring(3,5);
var anneedos = datedos.substring(6,10);
var datedosgf = moisdos+'/'+jourdos+"/"+anneedos;


                  var optionSet1 = {
                     startDate: dateunogf,
                    endDate: datedosgf,
                    minDate: '01/01/2010',
                    maxDate: '12/31/2020',
                    dateLimit: { days: 800 },
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
                  $('#reportrange').daterangepicker(optionSet1, cb);
                 $('#reportrange span').html($_GET('date1') + ' - ' + $_GET('date2'));
                  $('#reportrange').on('show.daterangepicker', function() { console.log("show event fired"); });
                  $('#reportrange').on('hide.daterangepicker', function() { console.log("hide event fired"); });
                  $('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
                    console.log("apply event fired, start/end dates are " 
                      + picker.startDate.format('DD MM, YYYY') 
                      + " to " 
                      + picker.endDate.format('DD MM, YYYY')                      
                    ); 
                    window.location.href = "jours.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY');
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
       
      
            </div>
            </div>    
            </div>
      <hr/>
      
         <h2>
         <?php
// on affiche la période visée
  if($_GET['date1'] == $_GET['date2']){
    echo' Le '.$_GET['date1']." : </h2>";
  }
  else
  {
  echo' Du '.$_GET['date1']." au ".$_GET['date2']." : </h2>";  
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







<div id="collectes" style="height: 250px;"></div>



<script>
new Morris.Area({
  // ID of the element in which to draw the chart.
  element: 'collectes',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
  

<?php 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT SUM( masse ) AS nombre, DATE( timestamp ) AS time
FROM pesees_collectes
WHERE  DATE(pesees_collectes.timestamp) BETWEEN :du AND :au 
GROUP BY DATE_FORMAT( timestamp,  "%Y-%m-%d" ) 
ORDER BY timestamp');
            $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "{y:'".$donnees['time']."', a:".$donnees['nombre'].", b:"."45"."},";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>



  ],
  xkey: 'y',
  ykeys: ['a', 'b'],
  labels: ['Series A', 'Series B']
});
</script>







<?php
 include "pied_bilan.php";
}
    else
    {header('Location: ../moteur/destroy.php') ;}
?>
