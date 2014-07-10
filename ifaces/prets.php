<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      {  include "tete.php" ?>
   
        <h1>pret d'objet stockés à la ressourcerie</h1> 
         


<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      