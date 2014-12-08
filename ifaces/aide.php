<?php session_start();
//Vérification du renseignement de la variable de session 'id':
    if (isset($_SESSION['id']) )
      {  include "tete.php" ?>
   
        <h1>need help? call me!</h1>
 <p>...and if I don't answer, bear in mind that a help file is actually under construction...and please be patient</p> 
         


<?php include "pied.php"; 
}
    else
   { header('Location: ../moteur/destroy.php') ; }
?>
       
      
