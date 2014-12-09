<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   
        <h1>Recettes filières de sortie</h1> 
         


<?php include "pied.php";
}
    else
{   
header('Location: ../moteur/destroy.php') ;
}
?>
       
      
