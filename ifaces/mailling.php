<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'm') !== false))
      {  include "tete.php" ?>
   
        <h1>recuperation de la lise de mails des adhérents</h1> 
         ici on peut supprimer un mail de la liste de mails (si un adherent le souhaite)
         <br>
         on peut exporter la liste complete des adherents mais aussi les mails uniquement en vue d'un mailing


<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../moteur/destroy.php') ;
?>
       
      