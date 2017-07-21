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
require_once('../moteur/dbconfig.php');

   if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {
     require_once('../moteur/dbconfig.php');
     require_once "tete.php"

     ?>

   <script type="text/javascript" src="../js/moment.js"></script>
   <script type="text/javascript" src="../js/daterangepicker.js"></script>
   <div class="container" style="width:1300px">
        <h1>Vérification des collectes</h1> 
 <div class="panel-body">
<ul class="nav nav-tabs">
 <?php 
          //on affiche un onglet par type d'objet
            // On recupère tout le contenu des visibles de la table type_dechets
            $reponse = $bdd->query('SELECT * FROM points_collecte');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?=  "verif_collecte.php?numero=" . $donnees['id']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>"><?=$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
       </ul>



<br>


<div class="row">
  <div class="col-md-3 col-md-offset-9" >
  <label for="reportrange">Choisissez la période à inspecter:</label><br>
<div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="fa fa-calendar"></i>
                  <span></span> <b class="caret"></b>
               </div>

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
                    //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
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
                    maxDate: '12/31/2030',
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

                  

                   $('#reportrange span').html($_GET('date1') + ' - ' + $_GET('date2'));

                  $('#reportrange').daterangepicker(optionSet1, cb);

                  $('#reportrange').on('show.daterangepicker', function() { console.log("show event fired"); });
                  $('#reportrange').on('hide.daterangepicker', function() { console.log("hide event fired"); });
                  $('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
                    console.log("apply event fired, start/end dates are " 
                      + picker.startDate.format('DD MM, YYYY') 
                      + " to " 
                      + picker.endDate.format('DD MM, YYYY')                      
                    ); 
                    window.location.href = "verif_collecte.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY')+"&numero="+"<?= $_GET['numero']?>";
                  });
                  $('#reportrange').on('cancel.daterangepicker', function(ev, picker) { console.log("cancel event fired"); });

                  $('#options1').click(function() {
                    $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
                  });

                 
                  $('#destroy').click(function() {
                    $('#reportrange').data('daterangepicker').remove();
                  });

               });
               </script>
        	
 </div>
<?php
// on affiche la période visée
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

<?php
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `collectes` 
                       WHERE collectes.id_point_collecte = :id_point_collecte AND DATE(collectes.timestamp) BETWEEN :du AND :au   ');
$req->execute(array('id_point_collecte' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>


  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Moment de la collecte</th>
            <th>Type de collecte</th>
            <th>Commentaire</th>
            <th>Localité</th>
            <th>Masse totale</th>
            <th>Créée par</th>
            <th></th>
            <th>Modifié par</th>
            <th>Le:</th>
          </tr>
        </thead>
        <tbody>
        <?php 
/*
'SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
FROM type_dechets,pesees_collectes 
WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
GROUP BY nom'
*/


 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare('SELECT collectes.id,collectes.timestamp ,type_collecte.nom, collectes.commentaire, localites.nom localisation, utilisateurs.mail mail , collectes.last_hero_timestamp lht
                       FROM collectes ,type_collecte, localites,utilisateurs
                       WHERE type_collecte.id = collectes.id_type_collecte
                       
                        AND utilisateurs.id = collectes.id_createur
                        AND localites.id = collectes.localisation  
                        AND collectes.id_point_collecte = :id_point_collecte 
                        AND DATE(collectes.timestamp) BETWEEN :du AND :au  ');
$req->execute(array('id_point_collecte' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch()) {
           ?>
            <tr>
            <td style="height:20px"><?= $donnees['id']?></td>
            <td style="height:20px"><?= $donnees['timestamp']?></td>
            <td style="height:20px"><?= $donnees['nom']?></td>
            <td width="20%" style="height:20px"><?= $donnees['commentaire']?></td>
            <td style="height:20px"><?= $donnees['localisation']?></td>
            <td style="height:20px">
 <?php
$req2 = $bdd->prepare('SELECT SUM(pesees_collectes.masse) masse
                       FROM pesees_collectes
                       WHERE  pesees_collectes.id_collecte = :id_collecte ');
$req2->execute(array('id_collecte' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?= $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 



<td><?= $donnees['mail']?></td> 

<td>

<form action="modification_verification_collecte.php?ncollecte=<?= $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?= $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']?>">
<input type="hidden" name ="localisation" id="localisation" value="<?= $donnees['localisation']?>">
<input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>

<td>

<?php 
$req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, collectes
                       WHERE  collectes.id = :id_collecte 
                       AND utilisateurs.id = collectes.id_last_hero');
$req3->execute(array('id_collecte' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           { ?>



<?= $donnees3['mail']?>


         <?php }
            $req3->closeCursor(); // Termine le traitement de la requête 3
                ?>

</td> 
<td><?php if ($donnees['lht'] !== '0000-00-00 00:00:00'){echo $donnees['lht'];}?></td> 




          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
              $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tfoot>
        
      </table>

<?php
    } else{echo 'Pas de correspondance trouvée pour cette période<br><br>';
    $req->closeCursor(); }
}
?>




  </div><!-- /.container -->
<?php include "pied.php";
}
    else
{   
header('Location: ../moteur/destroy.php') ;
}
?>
       
      
