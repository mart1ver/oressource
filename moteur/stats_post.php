<?php session_start();

//on definit $adh en fonction $_POST['adh']
if(isset($_POST['adh']))
    {
    $adh = "oui";
    }
  else
  {
   $adh = "non";
  }


  if ($_POST['saisiec_user'] == "oui" AND (strpos($_POST['niveau_user'], 'e') !== false) )
   {
    //avec antidate
$antidate = $_POST['antidate'].date(" H:i:s");
    // Connexion à la base de données





  $i = 1;
while ($i <= $_POST['nlignes'])
{
   //on inserre les valeures pour chaque 'i' ($i = une ligne dans le ticket)   
   // Insertion du post à l'aide d'une requête préparée

$tid_type_objet = 'tid_type_objet'.$i;
if(isset($_POST[$tid_type_objet]))
    {
$tid_objet ='tid_objet'.$i;
$tquantite = 'tquantite'.$i;
$tprix = 'tprix'.$i;
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$req = $bdd->prepare('INSERT INTO vendus (timestamp, id_vente,  id_type_dechet, id_objet, quantite, prix, id_createur) VALUES(?, ?,?, ?, ?, ?, ?)');
$req->execute(array($antidate, "0" ,  $_POST[$tid_type_objet] ,  $_POST[$tid_objet] ,  $_POST[$tquantite], "0", $_POST['id_user']));
  $id_vendu = $bdd->lastInsertId();
  $req->closeCursor();

//puis on inserre les pesées_vendus si ils existent sur la ligne du ticket 

  $tmasse = 'tmasse'.$i; 
if(isset($_POST[$tmasse]))// si tmasse.$i present sur la ligne,
    {
      //on inserre
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$req = $bdd->prepare('INSERT INTO pesees_vendus (timestamp,id_vendu,  masse,quantite, id_createur) VALUES(?,?,?,?,?)');
$req->execute(array($antidate,$id_vendu ,  $_POST[$tmasse] ,$_POST[$tquantite],  $_POST['id_user']));
  $req->closeCursor();

    }
}
    $i++;
}
// Redirection du visiteur vers la page de ventes
 header("Location:../ifaces/pesees_stats.php");
  }else      {
//sans antidate






  $i = 1;
while ($i <= $_POST['nlignes'])
{
   //on inserre les valeures pour chaque 'i' ($i = une ligne dans le ticket)   
   // Insertion du post à l'aide d'une requête préparée

$tid_type_objet = 'tid_type_objet'.$i;
if(isset($_POST[$tid_type_objet]))
    {
$tid_objet ='tid_objet'.$i;
$tquantite = 'tquantite'.$i;
$tprix = 'tprix'.$i;




try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$req = $bdd->prepare('INSERT INTO vendus (id_vente,  id_type_dechet, id_objet, quantite, prix, id_createur) VALUES(?,?, ?, ?, ?, ?)');
$req->execute(array("0" ,  $_POST[$tid_type_objet] ,  $_POST[$tid_objet] ,  $_POST[$tquantite], "0", $_POST['id_user']));
$id_vendu = $bdd->lastInsertId();
  $req->closeCursor();

//puis on inserre les pesées_vendus si ils existent sur la ligne du ticket 

  $tmasse = 'tmasse'.$i; 
if(isset($_POST[$tmasse]))// si tmasse.$i present sur la ligne,
    {
      //on inserre
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$req = $bdd->prepare('INSERT INTO pesees_vendus (id_vendu,  masse,quantite, id_createur) VALUES(?,?,?,?)');
$req->execute(array($id_vendu ,  $_POST[$tmasse],$_POST[$tquantite] ,  $_POST['id_user']));
  $req->closeCursor();

    }
}



    $i++;
}


// Redirection du visiteur vers la page de gestion des affectation
	header("Location:../ifaces/pesees_stats.php");
}

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'g'.$_GET['numero']) !== false))
{}
else { 
  header('Location:../moteur/destroy.php?motif=1');
     }
?>
