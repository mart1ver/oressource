<?php session_start();

require_once('../moteur/dbconfig.php');

?>
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
      {  include "tete.php" ?>
   <div class="container">
        <h1>verification des ventes</h1> 
 <div class="panel-body">
<ul class="nav nav-tabs">


 <?php 
          //on affiche un onglet par type d'objet
            // On recupère tout le contenu des visibles de la table type_dechets
            $reponse = $bdd->query('SELECT * FROM points_vente');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "verif_vente.php?numero=" . $donnees['id']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
       </ul>



<br>


<div class="row">



          <div class="col-md-3 col-md-offset-9" >
  <label for="reportrange">Choisissez la période à inspecter::</label><br>
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
                    window.location.href = "verif_vente.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY')+"&numero="+"<?php echo $_GET['numero']?>";
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

</div>


  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Momment de la vente</th>
            <th>Crédit</th>
            <th>Débit</th>
            <th>Nombre d'objets</th>
            <th>Moyen de paiement</th>
            <th>Commentaire</th>
            <th>Auteur de la ligne</th>
            <th></th>
            <th>Modifié par</th>
            <th style="width:100px">Le</th>
            
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
          
$req = $bdd->prepare('SELECT ventes.id,ventes.timestamp ,moyens_paiement.nom moyen, moyens_paiement.couleur coul, ventes.commentaire ,ventes.last_hero_timestamp lht 
                       FROM ventes ,moyens_paiement 
                       WHERE ventes.id_point_vente = :id_point_vente 
                       AND ventes.id_moyen_paiement = moyens_paiement.id
                       AND DATE(ventes.timestamp) BETWEEN :du AND :au ');
$req->execute(array('id_point_vente' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>


            <td> <?php 
 
            // Si tout va bien, on peut continuer
$req2 = $bdd->prepare('SELECT SUM(vendus.prix*vendus.quantite) pto
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente 
                       ');
$req2->execute(array('id_vente' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php if ( $donnees2['pto'] > 0){echo $donnees2['pto'];
$rembo = 'non';
}?>


         <?php }
            
                ?></td>
            <td><?php 
 
            // Si tout va bien, on peut continuer
$req3 = $bdd->prepare('SELECT SUM(vendus.remboursement*vendus.quantite) pto
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente 
                       ');
$req3->execute(array('id_vente' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           { ?>



<?php if ( $donnees3['pto'] > 0){echo $donnees3['pto'];
$rembo = 'oui';
}?>


         <?php }
            
                ?></td>
            <td><?php 
$req4 = $bdd->prepare('SELECT SUM(vendus.quantite) pto
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente 
                       ');
$req4->execute(array('id_vente' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees4 = $req4->fetch())
           { ?>



<?php if ( $donnees4['pto'] > 0){echo $donnees4['pto'];}?>


         <?php }
            
                ?></td>



            <td> <span class="badge" style="background-color:<?php echo$donnees['coul']?>"><?php echo $donnees['moyen']?></span></td>
            <td style="width:100px"><?php echo $donnees['commentaire']?></td>
            <td><?php 
 
            // Si tout va bien, on peut continuer
$req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, ventes
                       WHERE  ventes.id = :id_vente 
                       AND utilisateurs.id = ventes.id_createur');
$req5->execute(array('id_vente' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees5 = $req5->fetch())
           { ?>



<?php echo $donnees5['mail']?>


         <?php }
           
                ?></td> 
            <td>


<?php echo $donnees3['pto'];
echo $donnees4['pto'];

 if ( $rembo == 'non'){?>

              <form action="modification_verification_vente.php?nvente=<?php echo $donnees['id']?>" method="post">
<input type="hidden" name ="moyen" id="moyen" value="<?php echo $donnees['moyen']?>">
<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_GET['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_GET['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>


<?php } if (  $rembo == 'oui'){?>

              <form action="modification_verification_remboursement.php?nvente=<?php echo $donnees['id']?>" method="post">
<input type="hidden" name ="moyen" id="moyen" value="<?php echo $donnees['moyen']?>">
<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_GET['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_GET['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>


<?php } ?>

            </td>
            <td><?php 
 
            // Si tout va bien, on peut continuer
$req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, ventes 
                       WHERE  ventes.id = :id_vente 
                       AND utilisateurs.id = ventes.id_last_hero');
$req5->execute(array('id_vente' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees5 = $req5->fetch())
           { ?>



<?php echo $donnees5['mail']?>


         <?php }
           
                ?></td>

            <td><?php if ($donnees['lht'] !== '0000-00-00 00:00:00'){echo $donnees['lht'];}?></td>






          </tr>
           <?php }
                $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                $req3->closeCursor(); // Termine le traitement de la requête3
                $req4->closeCursor(); // Termine le traitement de la requête4
                $req5->closeCursor(); // Termine le traitement de la requête 5                ?>
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
            <th></th>
            
          </tfoot>
        
      </table>





  </div><!-- /.container -->
<?php include "pied_bilan.php";
}
    else
{
header('Location: ../moteur/destroy.php') ;
}
?>
       
      
