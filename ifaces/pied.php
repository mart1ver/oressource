<script src="../js/jquery-2.0.3.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<div class="container">
  <footer>
    <p class="text-left">Oressource 2014-2017</p>
    <?php if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource') { ?>
      <p class="text-right">
        <a href="../moteur/destroy.php">Déconnexion</a>
      </p>
    <?php } ?>
  </footer>
</div>
</body>
</html>
