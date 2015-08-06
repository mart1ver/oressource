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
   </head>
 

      <div class="container">
         

          
<div class"row">
  <div class="col-md-11 " >
<h1>Bilan global</h1>

   <div class="col-md-4 col-md-offset-8" >
<label for="reportrange">Choisissez la période à inspecter::</label><br>
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
                    window.location.href = "bilanhb.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY')+"&numero=<?php echo $_GET['numero'] ?>";
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

  <li ><a href="<?php echo  "bilanc.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2'].'&numero=0'?>">Collectes</a></li>
  <li class="active"><a>Sorties hors-boutique</a></li>
  <li><a href="<?php echo  "bilanv.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2'].'&numero=0'?>">Ventes</a></li>
  
</ul>
      
         
  </div>

      </div>    

 
  
      </div>
      



<div class="row">
   <div class="col-md-8 col-md-offset-1" >
  <h2> Bilan des sorties hors-boutique de la structure 
  </h2>
  <ul class="nav nav-tabs">
 

 <?php 
            // On recupère tout le contenu des visibles de la table type_dechets
            $reponse = $bdd->query('SELECT * FROM points_sortie');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "bilanhb.php?numero=" . $donnees['id']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>"> <?php echo$donnees['nom']?> </a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
           <li<?php if ($_GET['numero'] == 0){ echo ' class="active"';}?>><a href="<?php echo  "bilanhb.php?numero=0" ."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>">Tous les points</a></li>
       </ul>

  <br>

<div class="row">
  <h2>
    <?php
// on affiche la période visée
  if($_GET['date1'] == $_GET['date2']){
    echo' Le '.$_GET['date1']." ,";
  }
  else
  {
  echo' Du '.$_GET['date1']." au ".$_GET['date2']." ,";  
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
  masse totale évacuée: <?php
// on determine la masse totale collecté sur cette période (pour tous les points)
           
if ($_GET['numero'] == 0) {
            // On recupère tout le contenu de la table point de vente
$req = $bdd->prepare("SELECT SUM(pesees_sorties.masse)AS total   FROM pesees_sorties  WHERE  pesees_sorties.timestamp BETWEEN :du AND :au ");
$req->execute(array('du' => $time_debut,'au' => $time_fin ));
$donnees = $req->fetch();
$mtotcolo = $donnees['total'];
echo $donnees['total']." Kgs.";
            
              $req->closeCursor(); // Termine le traitement de la requête
               
}
else //si on observe un point en particulier
{
            // On recupère tout le contenu de la table point de vente
$req = $bdd->prepare("SELECT SUM(pesees_sorties.masse)AS total  
FROM pesees_sorties ,sorties
WHERE pesees_sorties.id_sortie = sorties.id 
AND pesees_sorties.timestamp BETWEEN :du AND :au  AND sorties.id_point_sortie = :numero ");
$req->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
$donnees = $req->fetch();
$mtotcolo = $donnees['total'];
echo $donnees['total']." Kgs.";
            
              $req->closeCursor(); // Termine le traitement de la requête
}
if ($_GET['numero'] == 0) {
  ?>
  , sur <?php
// on determine le nombre de points de collecte
            /*
            */
 $req = $bdd->prepare("SELECT COUNT(id) FROM points_sortie");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('au' => $time_fin ));
$donnees = $req->fetch();
     
echo $donnees['COUNT(id)'];
$req->closeCursor(); // Termine le traitement de la requête
  ?> Point(s) de sorties.

<?php } ?></h2>
  <div class="col-md-6">        


<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Répartition par classe de sorties
</h3>
  </div>
  <div class="panel-body">
    
<table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
    <thead>
        <tr>
            <th  style="width:300px">Classe:</th>
            <th>Nbr.de bons de sortie</th>
            <th>Masse évacuée</th>

            <th>%</th>
            
        </tr>
    </thead>
    <tbody>
       


        <?php
        if ($_GET['numero'] == 0) {
// on determine les masses totales évacuées sur cete période(pour Tous les points)
            
            $reponse = $bdd->prepare('SELECT 
SUM(pesees_sorties.masse) somme,pesees_sorties.timestamp,sorties.classe,COUNT(distinct sorties.id) ncol
FROM 
pesees_sorties,sorties
WHERE
  pesees_sorties.timestamp BETWEEN :du AND :au  AND pesees_sorties.id_sortie = sorties.id
GROUP BY classe');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            ?>
            <tr data-toggle="collapse" data-target=".parmasse<?php echo $donnees['classe']?>" >

<?php switch ($donnees['classe'])
{
case 'sortiesc';?>
<td>don aux partenaires</td>
<?php break;
case 'sorties';?>
<td>don</td>
<?php break;
case 'sortiesd';?>
<td>dechetterie</td>
<?php break;
case 'sortiesp';?>
<td>poubelles</td>
<?php break;
case 'sortiesr';?>
<td>recycleurs</td>
<?php break;
default; ?>
<td>base érronée</td>
<?php
}
?>
            


            <td><?php echo $donnees['ncol'] ?></td>
            <td><?php echo $donnees['somme'] ?></td>
            <td><?php echo  round($donnees['somme']*100/$mtotcolo, 2)   ; ?></td>      
        </tr>

  
            
                       





            




 <?php
             }



              
              $reponse->closeCursor(); // Termine le traitement de la requête
               






               }else
               {



               } ?>

      </tbody>
</table>




<br>


  
          
          
          
       <br>
<a href="<?php echo  "../moteur/export_bilanc_partype.php?numero=". $_GET['numero']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>">



      
        <button type="button" class="btn btn-default btn-xs">exporter ces données (.csv) </button>
      </a>
</div>
  </div>



  </div>
 


  <div class="col-md-6">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Répartition par localité
</h3>
  </div>
  <div class="panel-body">
    
<table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
    <thead>
        <tr>
            <th  style="width:300px">Localité</th>
            <th>Nbr.de bons de sorties</th>
            <th>Masse évacuée</th>

            <th>%</th>
            
        </tr>
    </thead>
    <tbody>
       


        <?php
        if ($_GET['numero'] == 0) {
// on determine les masses totales collèctés sur cete période(pour Tous les points)
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT 
localites.nom,SUM(pesees_collectes.masse) somme,pesees_collectes.timestamp,localites.id id,COUNT(distinct collectes.id) ncol
FROM 
pesees_collectes,collectes,localites
WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
GROUP BY id');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            ?>
            <tr data-toggle="collapse" data-target=".parloc<?php echo $donnees['id']?>" >
            <td><?php echo $donnees['nom'] ?></td>
            <td><?php echo $donnees['ncol'] ?></td>
            <td><?php echo $donnees['somme'] ?></td>
            <td><?php echo  round($donnees['somme']*100/$mtotcolo, 2)   ; ?></td>      
        </tr>

      <?php 
            // On recupère tout le contenu de la table affectations
            $reponse2 = $bdd->prepare('SELECT localites.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,localites , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au 
AND type_dechets.id = pesees_collectes.id_type_dechet 
AND localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
AND localites.id = :id_loc
GROUP BY nom
ORDER BY somme DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin ,'id_loc' => $donnees['id'] ));
           // On affiche chaque entree une à une
           while ($donnees2 = $reponse2->fetch())
           {        
            ?>

    <tr class="collapse parloc<?php echo $donnees['id']?> " >
            <td  >
              <?php echo $donnees2['nom'] ?>
            </td >
            <td >
                <?php echo $donnees2['somme']." Kgs." ?>
            </td>
            <td >
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
               }else
               {
// on determine les masses totales collèctés sur cete période(pour un point donné)
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT 
localites.nom,SUM(pesees_collectes.masse) somme,pesees_collectes.timestamp,localites.id,COUNT(distinct collectes.id) ncol
FROM 
pesees_collectes,collectes,localites
WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
AND collectes.id_point_collecte = :numero
GROUP BY id
');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero']  ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            ?>
            <tr data-toggle="collapse" data-target=".parloc<?php echo $donnees['id']?>" >
            <td><?php echo $donnees['nom'] ?></td>
             <td><?php echo $donnees['ncol'] ?></td>
            <td><?php echo $donnees['somme'] ?></td>
            <td><?php echo  round($donnees['somme']*100/$mtotcolo, 2)   ; ?></td>      
        </tr>
      <?php 
            // On recupère tout le contenu de la table affectations
            $reponse2 = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,type_collecte , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au 
AND type_dechets.id = pesees_collectes.id_type_dechet 
AND type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
AND type_collecte.id = :id_type_collecte AND collectes.id_point_collecte = :numero
GROUP BY nom
ORDER BY somme DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ,'id_type_collecte' => $donnees['id'] ));
           // On affiche chaque entree une à une
           while ($donnees2 = $reponse2->fetch())
           {        
            ?>
 <tr class="collapse parloc<?php echo $donnees['id']?> active">
    
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
               } ?>

      








    </tbody>
</table>




<br>

<div id="graphloca" style="height: 180px;"></div>
<br>
<div id="graph2loca" style="height: 180px;"></div>          

<br>
       <a href="<?php echo  "../moteur/export_bilanc_parloca.php?numero=". $_GET['numero']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>">
        <button type="button" class="btn btn-default btn-xs">exporter ces données (.csv) </button>
      </a>
  </div>
</div>

  </div>
 

 <script>       Morris.Donut({
    element: 'graphmasse',
   
data: [
<?php 
if ($_GET['numero'] == 0) {
            // On recupère tout les masses collectés pour chaque type
            $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme 
              FROM type_collecte,pesees_collectes,collectes WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id AND DATE(collectes.timestamp) BETWEEN :du AND :au
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère les couleurs de chaque type
            $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme 
              FROM type_collecte,pesees_collectes,collectes WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id AND DATE(collectes.timestamp) BETWEEN :du AND :au
GROUP BY nom');
  $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '"'.$donnees['couleur'].'"'.',';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>
<?php }
else {
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme 
              FROM type_collecte,pesees_collectes,collectes 
              WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme 
              FROM type_collecte,pesees_collectes,collectes 
              WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '"'.$donnees['couleur'].'"'.',';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>
<?php } ?>


 <script>       Morris.Donut({
    element: 'graph2masse',
   
data: [
<?php 
if ($_GET['numero'] == 0) {
            // On recupère tout les masses collectés pour chaque type
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère les couleurs de chaque type
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
  $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo "'".$donnees['couleur']."'".",";
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>
<?php }
else {
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes,collectes 
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes,collectes 
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo "'".$donnees['couleur']."'".",";
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>
<?php } ?>


 <script>       Morris.Donut({
    element: 'graphloca',
   
data: [
<?php 
if ($_GET['numero'] == 0) {
            // On recupère tout les masses collectés pour chaque type
            $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(distinct pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes,collectes,localites WHERE localites.id = collectes.localisation AND pesees_collectes.id_collecte = collectes.id AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère les couleurs de chaque type
            $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(distinct pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes,collectes,localites WHERE localites.id = collectes.localisation AND pesees_collectes.id_collecte = collectes.id AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
  $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo "'".$donnees['couleur']."'".",";
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>
<?php }
else {
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(pesees_collectes.masse) somme 
              FROM localites,pesees_collectes,collectes 
              WHERE localites.id = collectes.localisation AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(pesees_collectes.masse) somme 
              FROM localites,pesees_collectes,collectes 
              WHERE localites.id = collectes.localisation AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo "'".$donnees['couleur']."'".",";
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>



<?php } ?>

<script>       Morris.Donut({
    element: 'graph2loca',
   
data: [
<?php 
if ($_GET['numero'] == 0) {
            // On recupère tout les masses collectés pour chaque type
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère les couleurs de chaque type
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
  $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo "'".$donnees['couleur']."'".",";
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>
<?php }
else {
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes,collectes 
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo '{value:'.$donnees['somme'].', label:"'.$donnees['nom'].'"},';
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
<?php 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
              FROM type_dechets,pesees_collectes,collectes 
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au 
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            echo "'".$donnees['couleur']."'".",";
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>



<?php } ?>
</div>

<br>
 

       
</div>
        </div>

 







   


<?php include "pied_bilan.php";
}
    else
    {header('Location: ../moteur/destroy.php') ;}
?>
