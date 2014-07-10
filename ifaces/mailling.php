<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      {  include "tete.php" ?>
   
        <h1>recuperation de la lise de mails des adhérents</h1> 
         


<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      