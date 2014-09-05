            <?php session_start(); 
//Oressource 2014, formulaire de description de la structure
//Simple formulaire de saisie renseignant les informations de base a sujet de la  structure
//
//
//
//
//
//
//
            if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
            { 
            include "tete.php" 
            ?>
<div class="container">
<h1>Description de la structure</h1> 
  <div class="panel-heading"> 
  </div>
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
            // On recupère tout le contenu de la table description structure
            $reponse = $bdd->query('SELECT * FROM description_structure');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
           {
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
    <form action="../moteur/edition_description_post.php" method="post">
      <div class="col-md-3 col-md-offset-2"><label for="nom">Nom de la structure:</label> <input type="text"value ="<?php echo $donnees['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
      <div class="col-md-4"><label for="adresse">Addresse:</label> <input type="text"       value ="<?php echo $donnees['adresse']; ?>" name="adresse" id="adresse" class="form-control " required ></div>
      <div class="col-md-2"><label for="telephone">Telephone:</label> <input type="tel" value ="<?php echo $donnees['telephone']; ?>" name="telephone" id="telephone" class="form-control " required ></div>
      <div class="col-md-2"></div>
    </div>
  <br>
    <div class="row">
      <div class="col-md-3 col-md-offset-2"><label for="localite">Localité:</label> <input type="text" value ="<?php echo $donnees['id_localite']; ?>" name="localite" id="localite" class="form-control " required > 
        <br>
        <label for="mail">Mail principal:</label> <input type="email" name="mail" id="mail" class="form-control " value = "<?php echo $donnees['mail']; ?>" required > 
      </div>
      <div class="col-md-2"><label for="siret">Numero de siret:</label> <input type="text" value ="<?php echo $donnees['siret']; ?>" name="siret" id="siret" class="form-control " required ></div>
      <div class="col-md-4"><label for="description">Presentation globale de la strucure</label> <textarea name="description" id="description" rows="10" cols="50" required><?php echo $donnees['description']; ?></textarea> </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-md-offset-2"><label for="description">Texte de présentation dédié aux adhésions</label> <textarea name="texte_adhesion" id="texte_adhesion" rows="10" cols="50" required><?php echo $donnees['texte_adhesion']; ?></textarea> </div>
    </div>
    <div class="row">
      <div class="col-md-1 col-md-offset-6"><br><button name="creer" class="btn btn-default">Enregistrer</button></div>
    </div>
    </form>
    </div>
  </div>
</div>
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
              ?>
              <?php include "pied.php" ?>
              <?php }
              else
              header('Location: ../') ;
              ?>