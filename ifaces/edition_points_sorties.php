                        <?php session_start(); ?>
                        <?php
                        if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'k') !== false))
                        { include "tete.php";
                        ?>
<div class="container">
<h1>Gestion des points de sortie hors boutique</h1> 
  <div class="panel-heading">Gérez ici les points de sortie hors boutique.</div>
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
    <form action="../moteur/edition_points_sortie_post.php" method="post">
      <div class="col-md-3"><label for="nom">Nom:</label><br><br><input type="text"                 value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
      <div class="col-md-3"><label for="addresse">Addresse:</label><br><br><input type="text"       value ="<?php echo $_GET['adresse']?>" name="adresse" id="adresse" class="form-control " required ></div>
      <div class="col-md-2"><label for="commentaire">Commentaire:</label><br><br><input type="text" value ="<?php echo $_GET['commentaire']?>" name="commentaire" id="commentaire" class="form-control " required ></div>
      <div class="col-md-2"><label for="pesee_max">Masse maxi. d'une pesée (Kg):</label> <input type="text" value ="<?php echo $_GET['pesee_max']?>" name="pesee_max" id="pesee_max" class="form-control " required ></div>
      <div class="col-md-1"><label for="couleur">Couleur:</label><br><br><input type="color"        value ="<?php if(isset($_GET['couleur']))echo "#".$_GET['couleur']?>" name="couleur" id="couleur" class="form-control " required ></div>
      <div class="col-md-1"><br><br><button name="creer" class="btn btn-default">Creer!</button></div>
    </form>
  </div>
</div>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>Date de creation:</th>
      <th>Nom:</th>
      <th>Adresse:</th>
      <th>Couleur:</th>
      <th>Commentaire:</th>
      <th>Pesée maximum:</th>
      <th>visible</th>
      <th>Modifier</th>
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
                        $reponse = $bdd->query('SELECT * FROM points_sortie');
                                     // On affiche chaque entree une à une
                        while ($donnees = $reponse->fetch())
                        {
                        ?>
    <tr> 
      <td><?php echo $donnees['id']?></td>
      <td><?php echo $donnees['timestamp']?></td>
      <td><?php echo $donnees['nom']?></td>
      <td><?php echo $donnees['adresse']?></td>
      <td><span class="badge" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['couleur']?></span></td> 
      <td><?php echo $donnees['commentaire']?></td>
      <td><?php echo $donnees['pesee_max']?></td>
      <td>
        <form action="../moteur/sorties_visibles_post.php" method="post">
        <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
        <input type="hidden"name ="visible" id ="visible" value="<?php if ($donnees['visible'] == "oui") {echo "non";} else {echo "oui";}?>">
                          <?php
                          if ($donnees['visible'] == "oui") // SI on a pas de message d'erreur
                          {?>
        <button  class="btn btn-info btn-sm " >
                          <?php
                          }
                          else // SINON 
                          {?>
        <button  class="btn btn-danger btn-sm " >
                           <?php
                           }
                           echo $donnees['visible']?> 
        </button>
        </form>
      </td>
      <td>
        <form action="modification_points_sortie.php" method="post">
          <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
          <input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
          <input type="hidden" name ="adresse" id="adresse" value="<?php echo $donnees['adresse']?>">
          <input type="hidden" name ="commentaire" id="commentaire" value="<?php echo $donnees['commentaire']?>">
           <input type="hidden" name ="pesee_max" id="pesee_max" value="<?php echo $donnees['pesee_max']?>">
          <input type="hidden" name ="couleur" id="couleur" value="<?php echo substr($_POST['couleur'],1)?>">
          <button  class="btn btn-warning  btn-sm" >modifier</button>
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
</div>
   
                              <?php include "pied.php" ?>
                              <?php }
                              else
                              header('Location: ../moteur/destroy.php') ;
                              ?>
