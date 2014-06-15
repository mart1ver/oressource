


<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
      { include "tete.php" ?>




    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Bienvenue sur Oressource</h1>
        <p>L'outil libre de quantification et de mise en bilan pour ressourceries</p>
        <p><a class="btn btn-primary btn-sm" role="button">Plus d'infos. &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>collecté ajourdhui:</h2>
          <p><div id="graphj" style="height: 180px;"></div></p>
          <p><a class="btn btn-default" href="#" role="button">Details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>collecté ce mois:</h2>
          <p><div id="graphm" style="height: 180px;"></div></p>
          <p><a class="btn btn-default" href="#" role="button">Details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>collecté cette année:</h2>
          <p><div id="grapha" style="height: 180px;"></div></p>
          <p><a class="btn btn-default" href="#" role="button">Details &raquo;</a></p>
        </div>
      </div>
      <hr>
       </div> <!-- /container -->


    <!-- Bootstrap core JavaScript+morris+raphael
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
      <script src="../js/jquery-2.0.3.min.js"></script>
      <script src="../js/raphael.js"></script>
      <script src="../js/morris/morris.js"></script>
  <script>       Morris.Donut({
    element: 'graphj',
    data: [
    {value: 70, label: 'd3e'},
    {value: 15, label: <?php echo "'mobilier'" ?>},
    {value: 10, label: 'textile'},
    {value: 5, label: 'livres'}
    ],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
    '#0BA462',
    '#39B580',
    '#67C69D',
    '#95D7BB'
    ],
    formatter: function (x) { return x + "%"}
    });
</script>

<script>       Morris.Donut({
    element: 'graphm',
    data: [
    {value: 70, label: 'd3e'},
    {value: 15, label: <?php echo "'mobilier'" ?>},
    {value: 10, label: 'textile'},
    {value: 5, label: 'livres'}
    ],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
    '#0BA462',
    '#39B580',
    '#67C69D',
    '#95D7BB'
    ],
    formatter: function (x) { return x + "%"}
    });
</script>

<script>       Morris.Donut({
    element: 'grapha',
    data: [
    {value: 70, label: 'd3e'},
    {value: 15, label: <?php echo "'mobilier'" ?>},
    {value: 10, label: 'textile'},
    {value: 5, label: 'livres'}
    ],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
    '#4773a3',
    '#39B580',
    '#67C69D',
    '#95D7BB'
    ],
    formatter: function (x) { return x + "%"}
    });
</script>
  



<?php include "pied.php" ?>
<?php }
    else{
     include "login.php"; }?>


