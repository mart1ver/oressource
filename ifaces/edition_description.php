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

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page: 
            if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'k') !== false))

            {include "tete.php"

//Oressource 2014, formulaire de description de la structure
//Simple formulaire de saisie renseignant les informations fondamentales identifiant la  structure 
            ?>

<div class="container">
<h1>Configuration de Oressource</h1> 
<label>Description de la structure</label>
  <div class="panel-heading"> 
  </div>
            <?php 
            // On recupère tout le contenu de la table description structure
            $reponse = $bdd->query('SELECT * FROM description_structure');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
           {
           ?>
  <div class="panel-body">
    <div class="row">
    <form action="../moteur/edition_description_post.php" method="post">
      <div class="col-md-3 col-md-offset-1"><label for="nom">Nom de la structure:</label> <input type="text"value ="<?php echo $donnees['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
      <div class="col-md-4"><label for="adresse">Adresse:</label> <input type="text"       value ="<?php echo $donnees['adresse']; ?>" name="adresse" id="adresse" class="form-control " required ></div>
      <div class="col-md-2"><label for="telephone">Téléphone:</label> <input type="tel" value ="<?php echo $donnees['telephone']; ?>" name="telephone" id="telephone" class="form-control " required ></div>
      <div class="col-md-2"></div>
    </div>
  <br>
    <div class="row">
      <div class="col-md-4 col-md-offset-1"><label for="localite">Localité:</label> <input type="text" value ="<?php echo $donnees['id_localite']; ?>" name="localite" id="localite" class="form-control " required > 
        <br>
        <label for="mail">Mail principal:</label> <input type="email" name="mail" id="mail" class="form-control " value = "<?php echo $donnees['mail']; ?>" required > 
        <br>
        Permettre de dater formulaires (mode saisie):  <input name ="saisiec" id ="saisiec" type="checkbox" value = "oui" <?php if((strpos($donnees['saisiec'], 'oui') !== false)){ echo "checked";} ?> >
        <br>
        timeout session: <?//php echo $_SESSION['session_timeout'] ?> secondes
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
<div class="panel panel-default">
  <label class="panel-title">formulaire de ventes</label>
  <div class="panel-body">

Code de remboursement à la caisse: <input type="text" value ="<?php echo $donnees['cr']; ?>" name="cr" id="cr" class="form-control " required > 
<br>
Activer la Pesée à la caisse: <input name ="pes_vente" id ="pes_vente" type="checkbox" value = "oui" <?php if((strpos($donnees['pes_vente'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Interdire les ventes sans pesées: <input name ="force_pes_vente" id ="force_pes_vente" type="checkbox" value = "oui" <?php if((strpos($donnees['force_pes_vente'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Activer la vente par lot à la caisse: <input name ="lot" id ="lot" type="checkbox" value = "oui" <?php if((strpos($donnees['lot'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Activer la visualisation des ventes à la caisse: <input name ="viz" id ="viz" type="checkbox" value = "oui" <?php if((strpos($donnees['viz'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Nombre de ventes anterieures visibles: <input type="text" value ="<?php echo $donnees['nb_viz']; ?>" name="nb_viz" id="nb_viz" class="form-control " required >
<br>
 Activer la TVA à la vente : <input name ="atva" id ="atva" type="checkbox" value = "oui" <?php if((strpos($donnees['tva_active'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Taux en vigueur: <input type="text" value ="<?php echo $donnees['taux_tva']; ?>" name="ttva" id="ttva" class="form-control " required >
<br>
</div>
</div>




      </div>
       <div class="row">
      
      <div class="col-md-2"><label for="siret">Numéro de siret:</label> <input type="text" value ="<?php echo $donnees['siret']; ?>" name="siret" id="siret" class="form-control " required >

      </div>
      <div class="col-md-4"><label for="description">Présentation générale de la strucure:</label> <textarea name="description" id="description" rows="10" cols="50" required><?php echo $donnees['description']; ?></textarea> 

        <br>
        <br>
        <br>
<div class="panel panel-default">
  <label class="panel-title">formulaires de sorties hors boutique</label>
  <div class="panel-body">
Utiliser l'onglet "poubelles" :  <input name ="affsp" id ="lot" type="checkbox" value = "oui" <?php if((strpos($donnees['affsp'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Utiliser l'onglet "sorties partenaires" : <input name ="affss" id ="lot" type="checkbox" value = "oui" <?php if((strpos($donnees['affss'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Utiliser l'onglet "recyclage" :<input name ="affsr" id ="lot" type="checkbox" value = "oui" <?php if((strpos($donnees['affsr'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Utiliser l'onglet "don" : <input name ="affsd" id ="viz" type="checkbox" value = "oui" <?php if((strpos($donnees['affsd'], 'oui') !== false)){ echo "checked";} ?> >
<br>
Utiliser l'onglet "déchetterie" :<input name ="affsde" id ="lot" type="checkbox" value = "oui" <?php if((strpos($donnees['affsde'], 'oui') !== false)){ echo "checked";} ?> >
<br>
</div>
</div>
      </div>
    
  <br>
        


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
include "pied.php"; 
}
else
      { header('Location: ../moteur/destroy.php') ;}
?>
