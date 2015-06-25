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
   <div class="container" style="width:1300px">
        <h1>Vérification des collectes</h1> 
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
            // On recupère tout le contenu des visibles de la table type_dechets
            $reponse = $bdd->query('SELECT * FROM points_collecte');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "verif_collecte.php?numero=" . $donnees['id']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>"><?php echo$donnees['nom']?></a></li>
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
                    window.location.href = "verif_collecte.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY')+"&numero="+"<?php echo $_GET['numero']?>";
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
            <th>Type de collecte:</th>
            <th>Adhérent?:</th>
            <th>Localisation:</th>
            <th>Masse totale</th>
            <th>Auteur de la ligne</th>
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
          
$req = $bdd->prepare('SELECT collectes.id,collectes.timestamp ,type_collecte.nom, collectes.adherent, localites.nom localisation, utilisateurs.mail mail , collectes.last_hero_timestamp lht
                       FROM collectes ,type_collecte, localites,utilisateurs
                       WHERE type_collecte.id = collectes.id_type_collecte
                       
                        AND utilisateurs.id = collectes.id_createur
                        AND localites.id = collectes.localisation  
                        AND collectes.id_point_collecte = :id_point_collecte 
                        AND DATE(collectes.timestamp) BETWEEN :du AND :au  ');
$req->execute(array('id_point_collecte' => $_GET['numero'], 'du' => $time_debut,'au' => $time_fin));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td <td style="height:20px"><?php echo $donnees['id']?></td>
            <td style="height:20px"><?php echo $donnees['timestamp']?></td>
            <td style="height:20px"><?php echo $donnees['nom']?></td>
            <td style="height:20px"><?php echo $donnees['adherent']?></td>
            <td style="height:20px"><?php echo $donnees['localisation']?></td>
            <td style="height:20px"> 

 <?php 
$req2 = $bdd->prepare('SELECT SUM(pesees_collectes.masse) masse
                       FROM pesees_collectes
                       WHERE  pesees_collectes.id_collecte = :id_collecte ');
$req2->execute(array('id_collecte' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 



<td><?php echo $donnees['mail']?></td> 

<td>

<form action="modification_verification_collecte.php?ncollecte=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="localisation" id="localisation" value="<?php echo $donnees['localisation']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_GET['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_GET['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
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



<?php echo $donnees3['mail']?>


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
<?php include "pied_bilan.php";
}
    else
{   
header('Location: ../moteur/destroy.php') ;
}
?>
       
      
