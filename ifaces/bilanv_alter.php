
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
                   // alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
                  }

                  var optionSet1 = {
                    format: 'DD/MM/YYYY',
                    startDate: '<?php echo str_replace ( "-", "/", $_GET['date1'])?>',
                    endDate: '<?php echo str_replace ( "-", "/", $_GET['date2'])?>',
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
                  $('#reportrange').daterangepicker(optionSet1, cb);
                  $('#reportrange span').html(moment().format('D, MMMM, YYYY') + ' - ' + moment().format('D, MMMM, YYYY'));
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
      <hr/>
      <div class="row">
       <div class="col-md-8 col-md-offset-1" >
        <h2> Bilan des ventes de la structure</h2>
        <ul class="nav nav-tabs">
        <?php      //on affiche un onglet par point de vente
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
 
            // On recupère tout le contenu des visibles de la table points_vente
            $reponse = $bdd->query('SELECT * FROM points_vente');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "bilanv_alter.php?numero=" . $donnees['id']."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
              // sortisde la boucle on affiche un onglet special "touts les points"
           ?>

           <li<?php if ($_GET['numero'] == 0){ echo ' class="active"';}?>><a href="<?php echo  "bilanv_alter.php?numero=0" ."&date1=" . $_GET['date1']."&date2=" . $_GET['date2']?>">Tous les points</a></li>
       </ul>
       <br>
        <div class="row">
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
  
if ($_GET['numero'] == 0) // si numero == 0*****************************************************************************************************************************************
{
  echo "-nombre de points de vente : ";
  // on determine le nombre de points de vente à cet instant
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
 $req = $bdd->prepare("SELECT COUNT(id) FROM points_vente");
 $req->execute();
 $donnees = $req->fetch();
echo $donnees['COUNT(id)']."<br>";

  echo "-chiffre total dégagé (remboursemments déduits) : ";
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
  $req = $bdd->prepare("SELECT  (SUM(vendus.prix)-SUM(vendus.remboursement)) AS total   FROM vendus  WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au  ");
  $req->execute(array('du' => $time_debut,'au' => $time_fin ));
  $donnees = $req->fetch();
  $mtotcolo = $donnees['total'];
  echo $donnees['total']." €.<br>";
  $req->closeCursor(); // Termine le traitement de la requête
 echo "-nombre d'objets vendus : ";
// on determine le nombre d'objets vendus
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
 $req = $bdd->prepare("SELECT SUM(vendus.quantite) FROM vendus WHERE prix > 0 AND DATE(vendus.timestamp) BETWEEN :du AND :au ");
 $req->execute(array('du' => $time_debut,'au' => $time_fin ));
 $donnees = $req->fetch();
echo $donnees['SUM(vendus.quantite)']."<br>";
 echo "-nombre de ventes : ";
// on determine le nombre dde ventes
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
 $req = $bdd->prepare("SELECT COUNT(ventes.id) FROM ventes ,vendus WHERE vendus.id_vente = ventes.id AND DATE(vendus.timestamp) BETWEEN :du AND :au  AND vendus.prix > 0 ");
 $req->execute(array('du' => $time_debut,'au' => $time_fin ));
 $donnees = $req->fetch();
echo $donnees['COUNT(ventes.id)']."<br>";

  echo "-nombre d'objets remboursés' : ";
  // on determine le nombre d'objets remboursés
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
 $req = $bdd->prepare("SELECT SUM(vendus.quantite) FROM vendus WHERE remboursement > 0 AND DATE(vendus.timestamp) BETWEEN :du AND :au ");
 $req->execute(array('du' => $time_debut,'au' => $time_fin ));
 $donnees = $req->fetch();
echo $donnees['SUM(vendus.quantite)']."<br>";
  echo "-nombre de remboursemments : ";
  // on determine le nombre de remboursements
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
 $req = $bdd->prepare("SELECT COUNT(ventes.id) FROM ventes ,vendus WHERE vendus.id_vente = ventes.id AND DATE(vendus.timestamp) BETWEEN :du AND :au   AND vendus.remboursement > 0 ");
 $req->execute(array('du' => $time_debut,'au' => $time_fin ));
 $donnees = $req->fetch();
echo $donnees['COUNT(ventes.id)']."<br>";

  echo "-somme remboursée : ";
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
  $req = $bdd->prepare("SELECT  SUM(vendus.remboursement) AS total   FROM vendus  WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au  ");
  $req->execute(array('du' => $time_debut,'au' => $time_fin ));
  $donnees = $req->fetch();
  $mtotcolo = $donnees['total'];
  echo $donnees['total']." €.<br>";
  $req->closeCursor(); // Termine le traitement de la requête







}
else // si numero ==! 0*********************************************************************************************************************************************************
{
echo "-chiffre total dégagé : ";
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


$req = $bdd->prepare("SELECT (SUM(vendus.prix)-SUM(vendus.remboursement))  AS total  
FROM vendus ,ventes
WHERE DATE(ventes.timestamp) BETWEEN :du AND :au AND ventes.id_point_vente  = :numero AND ventes.id = vendus.id_vente");
$req->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ));
$donnees = $req->fetch();
$mtotcolo = $donnees['total'];
echo $donnees['total']." €.<br>";
$req->closeCursor(); // Termine le traitement de la requête
echo "-nombre d'objets vendus : ";
echo "-nombre de ventes : ";
echo "-nombre d'objets remboursés : ";
echo "-nombre de remboursemments : ";
echo "-somme remboursée : ";






}
?>
</div>
  </div>
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