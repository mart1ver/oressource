<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'a') !== false))
      {  include "tete.php" ?>
    <div class="container">
        <h1>Gestions des utilisateurs</h1> 
         <div class="panel-heading">Gerez ici les utilisateurs.</div>
         Niveau :  "a" pour admin
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
        <div class="row">
            <form action="../moteur/mdp_post.php" method="post">
  <div class="col-md-3"><label for="saisietitre">Nom:</label> <input type="text" value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
  <div class="col-md-3"><label for="saisiedescription">Mail:</label> <input type="email" value ="<?php echo $_GET['mail']?>" name="mail" id="mail" class="form-control " required ></div>
  <div class="col-md-3"><label for="saisiedescription">Mot de passe</label> <input type="password" value ="<?php echo $_GET['pass']?>" name="pass" id="pass" class="form-control " required ></div>
  <div class="col-md-2"><label for="saisiedescription">Niveau</label> <input type="text" value ="<?php echo $_GET['niveau']?>" name="niveau" id="niveau" class="form-control " required ></div>
  <div class="col-md-1"><br><button name="creer" class="btn btn-default">Creer!</button></div>
</form>
</div>
      </div>
      <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>nom</th>
            <th>mail</th>
            <th>niveau</th>
            <th>supprimer!</th>
            
          </tr>
        </thead>
        <tbody>
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
            $reponse = $bdd->query('SELECT * FROM utilisateurs');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['nom']?></td>
            <td><?php echo $donnees['mail']?></td>
            <td><?php echo $donnees['niveau']?></td>
            <td>







<form action="../moteur/sup_mdp.php" method="post">

  <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
  





 





   <button  class="btn btn-danger ">Suprimer! 
  </button>
</form>
</td>
          </tr>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
       </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            
          </tfoot>
        
      </table>
      <br>
      <div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-4"><br> </div>
  <div class="col-md-4"></div>
  </div>
  </div>
  </div>
    </div><!-- /.container -->
   
 <td style="vertical-align: top; background-color: rgb(204, 204, 255); height: 12px;"><small>Niveau : <br>
          <input type="checkbox" name="niveaug" id="niveaug" value="g"> gestion<br>
          <input type="checkbox" name="niveaua" id="niveaua" value="a"> admin<br>
          <input type="checkbox" name="niveaue" id="niveaue" value="e"> collecte<br>
          <input type="checkbox" name="niveauc" id="niveauc" value="c"> communication<br>
          <input type="checkbox" name="niveaub" id="niveaub" value="b"> boutiques<br>
          <input type="checkbox" name="niveaus" id="niveaus" value="s"> sorties hors boutique<br>
          <input type="checkbox" name="niveauh" id="niveauh" value="h"> adhesions<br>

        </small></td>


<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      