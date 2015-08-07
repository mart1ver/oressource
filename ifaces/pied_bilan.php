
<div class="row">
  <footer>
        <p class="text-left">Oressource 2014</p>
        <p class="text-right">
<?php
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
      { ?>
       <a href="../moteur/destroy.php">Déconnexion</a>
       <?php
      }
    else{ 
      header('Location: ../moteur/destroy.php') ;
}?>
</p>
</footer> 
</div>  
  </body>
</html>
