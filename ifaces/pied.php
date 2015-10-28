<script src="/js/jquery-2.1.1.min.js"></script>
<script src="/js/raphael.js"></script>
<script src="/js/morris/morris.js"></script>
<script src="/js/fetch.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/bootstrap-switch.js"></script>
<div class="container">
  <footer>
        <p class="text-left">Oressource 2014-2015</p>
        <p class="text-right">
<?php
if (isset($_SESSION['id'])
 && $_SESSION['systeme'] = "oressource") {
?>
       <a href="../moteur/destroy.php">Déconnexion</a>
<?php
} else{
  //header('Location: ../moteur/destroy.php') ;
}?>
</p>
</footer>
</div>
  </body>
</html>
