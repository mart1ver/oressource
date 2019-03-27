<div class="container">
  <footer>
    <p class="text-left">Oressource 2014-2019</p>
    <?php if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource') { ?>
      <p class="text-right">
        <a href="../moteur/destroy.php">Déconnexion</a>
      </p>
      <?php
    }
    ?>
  </footer>
</div>
</body>
</html>
