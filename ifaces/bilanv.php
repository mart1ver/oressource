
<?php session_start();

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
<label for="reportrange">Choisissez la période à inspecter:</label><br>

           

                      

            

            

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
                    startDate: <?php echo $_GET['date1']?>,
                    endDate: <?php echo $_GET['date2']?>,
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
                    window.location.href = "bilanv.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY')+"&numero=<?php echo $_GET['numero'] ?>";
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
 

  <li><a href="<?php echo  "bilanc.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2'].'&numero=0'?>" >Collectes</a></li>
  <li><a href="<?php echo  "bilanhb.php?date1=" . $_GET['date1'].'&date2='.$_GET['date2'].'&numero=0'?>">Sorties hors-boutique</a></li>

  <li class="active"><a>Ventes</a></li>
  
</ul>
      
         
  </div>

      </div>    

 
  
      </div>
      <hr />

<div class="row">
   <div class="col-md-8 col-md-offset-1" >
  <h2> Bilan des ventes de la structure 
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
            $reponse = $bdd->query('SELECT * FROM points_vente');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "bilanv.php?numero=" . $donnees['id']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
           <li<?php if ($_GET['numero'] == 0){ echo ' class="active"';}?>><a href="<?php echo  "bilanv.php?numero=0" ."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>">Tous les points</a></li>
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
  chiffre total dégagé: <?php
// on determine la masse totale collecté sur cette période (pour tous les points)


           
if ($_GET['numero'] == 0) {
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


$req = $bdd->prepare("SELECT SUM(vendus.prix) AS total   FROM vendus  WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au  ");
$req->execute(array('du' => $time_debut,'au' => $time_fin ));
$donnees = $req->fetch();
$mtotcolo = $donnees['total'];
echo $donnees['total']." €.";
            
              $req->closeCursor(); // Termine le traitement de la requête
               
}
else //si on observe un point en particulier
{

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


$req = $bdd->prepare("SELECT SUM(vendus.prix) AS total  
FROM vendus ,ventes
WHERE vendus.id_vente = ventes.id 
AND vendus.timestamp BETWEEN :du AND :au  AND ventes.id_point_vente  = :numero ");



$req->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
$donnees = $req->fetch();
$mtotcolo = $donnees['total'];
echo $donnees['total']." €.";
            
              $req->closeCursor(); // Termine le traitement de la requête



}
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
 $req = $bdd->prepare("SELECT COUNT(id) FROM points_vente");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('au' => $time_fin ));
$donnees = $req->fetch();
     
echo $donnees['COUNT(id)'];

$req->closeCursor(); // Termine le traitement de la requête



  ?> Point(s) de vente.

<?php } ?></h2>
  <div class="col-md-7">        





  




<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Répartition par type d'objet
</h3>
  </div>
  <div class="panel-body">
    
<table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
    <thead>
        <tr>
            <th  style="width:300px">Type d'objet</th>
            <th>Quantité vendue</th>
            <th>Chiffre dégagé</th>
            <th>Prix moyen</th>
            <th>%</th>
            
        </tr>
    </thead>
    <tbody>
       


        <?php
        if ($_GET['numero'] == 0) {

// on determine les masses totales collèctés sur cete période(pour Tous les points)
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
type_dechets.nom,type_dechets.id,SUM(vendus.quantite) sommeq,SUM(vendus.prix) sommep
FROM 
vendus,type_dechets, ventes
WHERE
vendus.timestamp BETWEEN :du AND :au AND
type_dechets.id =  vendus.id_type_dechet AND vendus.id_vente = ventes.id
GROUP BY id_type_dechet
ORDER BY sommep DESC');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            ?>
            <tr data-toggle="collapse" data-target=".parmasse<?php echo $donnees['id']?>" >
            <td><?php echo $donnees['nom'] ?></td>
            <td><?php echo $donnees['sommeq'] ?></td>
            <td><?php echo $donnees['sommep'] ?></td>
            <td><?php echo round($donnees['sommep']/$donnees['sommeq'],2) ?></td>     
            <td><?php echo round((100*$donnees['sommep'])/$mtotcolo,2)?> %</td> 
        </tr>

      <?php 
      $someqtot = 0;
      $someptot = 0;
      $percenttot = 0;

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
            $reponse2 = $bdd->prepare('SELECT IF(vendus.id_objet = 0, type_dechets.nom, grille_objets.nom) nom ,grille_objets.id, sum(vendus.quantite) sommeq, sum(vendus.prix) sommep
 FROM grille_objets, vendus ,ventes, type_dechets
WHERE vendus.timestamp BETWEEN :du AND :au 
AND grille_objets.id = vendus.id_objet 
AND type_dechets.id = vendus.id_type_dechet
AND vendus.id_vente = ventes.id
AND type_dechets.id = :id_type_dechet
GROUP BY nom
ORDER BY sommep DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin ,'id_type_dechet' => $donnees['id'] ));
           // On affiche chaque entree une à une
           while ($donnees2 = $reponse2->fetch())
           {        
            ?>

    <tr class="collapse parmasse<?php echo $donnees['id']?> " >
            <td  >
              <?php echo $donnees2['nom'] ?>
            </td >
            <td >
                <?php echo $donnees2['sommeq']." Pcs." ?>
            </td>
            <td >
                <?php echo $donnees2['sommep']." €." ?>
            </td>
            <td >
               <?php echo round((100*$donnees2['sommep'])/$mtotcolo,2)." %" ?> 
                           </td>
          </tr>
        
 <?php
 $someqtot = $someqtot + $donnees2['sommeq'] ;
 $someptot = $someptot + $donnees2['sommep'] ;
 $percenttot = $percenttot + round((100*$donnees2['sommep'])/$mtotcolo,2);
             }?>

             <tr class="collapse parmasse<?php echo $donnees['id']?> " >
            <td  >
              <?php echo "autres" ?>
            </td >
            <td >
                <?php echo $donnees['sommeq'] - $someqtot." Pcs." ?>
            </td>
            <td >
                <?php echo $donnees['sommep'] - $someptot." €." ?>
            </td>
            <td >
               <?php 
               if (round( ((100*$donnees['sommep'])/$mtotcolo - $percenttot)   ,2) > 0)
               {
               echo round( ((100*$donnees['sommep'])/$mtotcolo - $percenttot)   ,2)." %"; 
              }
              else
                {echo "0 %";}
               ?> 
                           </td>
          </tr>
             <?php

             
              $reponse2->closeCursor(); // Termine le traitement de la requête
               
           }
              $reponse->closeCursor(); // Termine le traitement de la requête
               }else

               {


// on determine les masses totales collèctés sur cete période(pour Tous les points)
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
type_dechets.nom,type_dechets.id,SUM(vendus.quantite) sommeq,SUM(vendus.prix) sommep
FROM 
vendus,type_dechets, ventes
WHERE
vendus.timestamp BETWEEN :du AND :au AND
type_dechets.id =  vendus.id_type_dechet AND vendus.id_vente = ventes.id AND ventes.id_point_vente = :numero
GROUP BY id_type_dechet
ORDER BY sommep DESC');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ,'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            ?>
            <tr data-toggle="collapse" data-target=".parmasse<?php echo $donnees['id']?>" >
            <td><?php echo $donnees['nom'] ?></td>
            <td><?php echo $donnees['sommeq'] ?></td>
            <td><?php echo $donnees['sommep'] ?></td>
            <td><?php echo round($donnees['sommep']/$donnees['sommeq'],2) ?></td>     
            <td><?php echo round((100*$donnees['sommep'])/$mtotcolo,2)?> %</td> 
        </tr>
      <?php 
      $someqtot = 0;
      $someptot = 0;
      $percenttot = 0;
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
            $reponse2 = $bdd->prepare('SELECT IF(vendus.id_objet = 0, type_dechets.nom, grille_objets.nom) nom ,grille_objets.id, sum(vendus.quantite) sommeq, sum(vendus.prix) sommep
 FROM grille_objets, vendus ,ventes, type_dechets
WHERE vendus.timestamp BETWEEN :du AND :au 
AND grille_objets.id = vendus.id_objet 
AND type_dechets.id = vendus.id_type_dechet
AND vendus.id_vente = ventes.id
AND type_dechets.id = :id_type_dechet
AND ventes.id_point_vente = :numero
GROUP BY nom
ORDER BY sommep DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin ,'id_type_dechet' => $donnees['id'],'numero' => $_GET['numero'] ));
           // On affiche chaque entree une à une
           while ($donnees2 = $reponse2->fetch())
           {        
            ?>

    <tr class="collapse parmasse<?php echo $donnees['id']?> " >
            <td  >
              <?php echo $donnees2['nom'] ?>
            </td >
            <td >
                <?php echo $donnees2['sommeq']." Pcs." ?>
            </td>
            <td >
                <?php echo $donnees2['sommep']." €." ?>
            </td>
            <td >
               <?php echo round((100*$donnees2['sommep'])/$mtotcolo,2)." %" ?> 
                           </td>
          </tr>
        
 <?php
 $someqtot = $someqtot + $donnees2['sommeq'] ;
 $someptot = $someptot + $donnees2['sommep'] ;
 $percenttot = $percenttot + round((100*$donnees2['sommep'])/$mtotcolo,2);
             }?>

             <tr class="collapse parmasse<?php echo $donnees['id']?> " >
            <td  >
              <?php echo "autres" ?>
            </td >
            <td >
                <?php echo $donnees['sommeq'] - $someqtot." Pcs." ?>
            </td>
            <td >
                <?php echo $donnees['sommep'] - $someptot." €." ?>
            </td>
            <td >
               <?php 
               if (round( ((100*$donnees['sommep'])/$mtotcolo - $percenttot)   ,2) > 0)
               {
               echo round( ((100*$donnees['sommep'])/$mtotcolo - $percenttot)   ,2)." %"; 
              }
              else
                {echo "0 %";}
               ?> 
                           </td>
          </tr>
             <?php

             
              $reponse2->closeCursor(); // Termine le traitement de la requête
               
           }
              $reponse->closeCursor(); // Termine le traitement de la requête
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
 


  















  <div class="col-md-5">



  </div>
 

 








</div>

<br>
 

       
</div>
        </div>

 







   


<?php include "pied_bilan.php";
}
    else
    {header('Location: ../moteur/destroy.php') ;}
?>