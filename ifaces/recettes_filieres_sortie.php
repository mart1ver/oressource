<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   
        <h1>recette filierres de sortie</h1> 
         


<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../moteur/destroy.php') ;
?>
       
      