
<?php session_start(); ?>

<?php

if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'a') !== false))
{
    ;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
  
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>inscription,modification de compte et désinscription à oressource</title>

  
</head><body>
inscription
<form action="../moteur/inscription_post.php" method="post">
  <table style="text-align: left; width: 340px; height: 89px;" cellpadding="2" cellspacing="2">
    <tbody>
      <tr height="14">
        <td style="vertical-align: top; background-color: rgb(204, 204, 255); height: 14px;"><small>Mail:<br>
        <input type="mail" name="mail" id="mail"><br>
        </small></td>
      </tr>
      <tr height="18">
        <td style="vertical-align: top; background-color: rgb(255, 255, 204); height: 18px;"><small>Mot
de passe:<br>
        <input  name="pass" id="pass" type="password" ><br>
        </small></td>
      </tr>
      <tr height="12">
        <td style="vertical-align: top; background-color: rgb(204, 204, 255); height: 12px;"><small>Niveau: <br>


<input type="checkbox" name="niveaug" id="niveaug" value="g"> gestion<br>
<input type="checkbox" name="niveaua" id="niveaua" value="a"> admin<br>
<input type="checkbox" name="niveaue" id="niveaue" value="e"> collecte<br>
<input type="checkbox" name="niveauc" id="niveauc" value="c"> communication<br>
<input type="checkbox" name="niveaub" id="niveaub" value="b"> boutiques<br>
<input type="checkbox" name="niveaus" id="niveaus" value="s"> sorties hors boutique<br>
<input type="checkbox" name="niveauh" id="niveauh" value="h"> adhesions<br>

        </small></td>
      </tr>
      <tr height="20">
        <td style="vertical-align: top; background-color: rgb(255, 255, 204); height: 20px; text-align: center;"><small><button name="colecter">Inscription!</button><br>
        </small></td>
      </tr>
    </tbody>
  </table>
  <br>
  <br>
edition
</form>
<form action="../moteur/inscription_edition_post.php" method="post">
  <table style="text-align: left; width: 340px; height: 89px;" cellpadding="2" cellspacing="2">
    <tbody>
      <tr height="14">
        <td style="vertical-align: top; background-color: rgb(204, 204, 255); height: 14px;"><small>Nom de l'utilisateur a editer<br>
        <select name="nom" >
	<option value="" selected></option>
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

// On récupère tout le contenu de la table membres
$reponse = $bdd->query('SELECT * FROM membres');

// On affiche chaque entrée une à une
while ($donnees = $reponse->fetch())
{
?>
    <option><?php echo $donnees['nom']; ?></option>
    
   
<?php
}

$reponse->closeCursor(); // Termine le traitement de la requête

?>

</select>  

        </small></td>
      </tr>
      <tr height="18">
        <td style="vertical-align: top; background-color: rgb(255, 255, 204); height: 18px;"><small>Mot
de passe:<br>
        <input name="pass" id="pass"type="password"><br>
        </small></td>
      </tr>
      <tr height="12">
        <td style="vertical-align: top; background-color: rgb(204, 204, 255); height: 12px;"><small>Niveau : <br>
          <input type="checkbox" name="niveaug" id="niveaug" value="g"> gestion<br>
          <input type="checkbox" name="niveaua" id="niveaua" value="a"> admin<br>
          <input type="checkbox" name="niveaue" id="niveaue" value="e"> collecte<br>
          <input type="checkbox" name="niveauc" id="niveauc" value="c"> communication<br>
          <input type="checkbox" name="niveaub" id="niveaub" value="b"> boutiques<br>
          <input type="checkbox" name="niveaus" id="niveaus" value="s"> sorties hors boutique<br>
          <input type="checkbox" name="niveauh" id="niveauh" value="h"> adhesions<br>

        </small></td>
      </tr>
      <tr height="20">
        <td style="vertical-align: top; background-color: rgb(255, 255, 204); height: 20px; text-align: center;"><small><button name="colecter">modifier le compte</button><br>
        </small></td>
      </tr>
    </tbody>
  </table>
  <br>
  <br>
</form>
supprimer un utilisateur:
<form action="../moteur/inscription_suppression_post.php" method="post">
  <table style="text-align: left; width: 340px; height: 89px;" cellpadding="2" cellspacing="2">
    <tbody>
      <tr height="14">
        <td style="vertical-align: top; background-color: rgb(204, 204, 255); height: 14px;"><small>Nom de l'utilisateur a suprimmer (irreversible)<br>
<select name="nom">
<option value="" selected></option>
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

// On récupère tout le contenu de la table membres
$reponse = $bdd->query('SELECT * FROM membres');

// On affiche chaque entrée une à une
while ($donnees = $reponse->fetch())
{
?>
    <option><?php echo $donnees['nom']; ?></option>
    
   
<?php
}

$reponse->closeCursor(); // Termine le traitement de la requête

?>



</select>  

            </small></td>
      </tr>
<tr height="20">
        <td style="vertical-align: top; background-color: rgb(255, 255, 204); height: 20px; text-align: center;"><small><button name="colecter">supprimer</button><br>
        </small></td>
      </tr>

     </tbody>
  </table>
  <br>
  <br>
</form>
<a href="../">index</a><br>
<a href="../moteur/destroy.php">se deconnecter</a><br>
</body></html>
<?php }
else
header('Location: ../');
?>
