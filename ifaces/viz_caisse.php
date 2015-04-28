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
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND $_SESSION['viz_caisse'] = "oui" AND (strpos($_SESSION['niveau'], 'v'.$_GET['numero']) !== false)  )
      {  include "tete.php" ?>
   <div class="container">
        <h1>Visualisation des <?php echo $_SESSION['nb_viz_caisse'] ?> derniere ventes</h1> 
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


<br>




</div>


  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>Crédit</th>
            <th>Débit</th>
            <th>Nombre d'objets</th>
            <th>Moyen de paiement</th>
            <th>Commentaire</th>
            <th>Auteur de la ligne</th>
            <th></th>
            <th>Modifié par</th>
            <th style="width:100px">Le:</th>
            
          </tr>
        </thead>
        <tbody>
        <?php 
        $offset = intval($_SESSION['nb_viz_caisse']);
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
                       AND ventes.id_moyen_paiement = moyens_paiement.id AND DATE(ventes.timestamp) =DATE(CURRENT_TIMESTAMP())
                       ORDER BY ventes.timestamp DESC
                       LIMIT 0,:offset ');

$req->bindValue('id_point_vente' , $_GET['numero'] , PDO::PARAM_INT );
$req->bindValue('offset' , $offset , PDO::PARAM_INT );
$req->execute();


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>


            <td> <?php 
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

              <form action="viz_vente.php?nvente=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_GET['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_GET['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Visualiser</button>


</form>


<?php } if (  $rembo == 'oui'){?>

              <form action="viz_remboursement.php?nvente=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_GET['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_GET['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Visualiser</button>


</form>


<?php } ?>

            </td>
            <td><?php 
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
       
      
