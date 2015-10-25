<?php

session_start();

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] = "oressource") {
  require_once("../moteur/dbconfig.php");

  // Vérification du renseignement du champ "id" (dans le tableau $_SESSION)
  // et du fait que la variable "système" de ce même tableau a bien la valeur
  // "oressource" avant d'afficher quoique ce soit:
  //

  include "tete_vente.php";

  $show_details = (boolean) (isset($_SESSION['id'])
    && $_SESSION['systeme'] = "oressource"
    && (strpos($_SESSION['niveau'], 'bi')));
    //on determine les masses collectés et evacuées ansi que le nombre d'objets vendus aujoud'hui
  $reponse = $bdd->query('
      SELECT SUM(vendus.quantite) quantity_sell,
          SUM(pesees_collectes.masse) mass_input,
          SUM(pesees_sorties.masse) mass_output
          FROM vendus, pesees_collectes, pesees_sorties
          WHERE DATE(vendus.timestamp) = CURDATE()
      AND DATE(pesees_collectes.timestamp) = CURDATE()
      AND DATE(pesees_sorties.timestamp) = CURDATE()
          AND vendus.remboursement = 0');

  $donnees = $reponse->fetch();
  $quantity_sell = ($donnees['quantity_sell'] == NULL) ? "0" : $donnees['quantity_sell'];
  $mass_input = ($donnees['mass_input'] == NULL) ? "0" : $donnees['mass_input'];
  $mass_output = ($donnees['mass_output'] == NULL) ? "0" : $donnees['mass_output'];
  $reponse->closeCursor(); // Termine le traitement de la requête
?>

    <div class="page-header">
      <div class="container">
        <h1>Bienvenue à bord d'Oressource <?php echo $_SESSION['prenom']?>! </h1>
        <p>Oressource est un outil libre de quantification et de mise en bilan dédié aux structures du ré-emploi</p>
      </div>
    </div>
    <div class="container" id="actualise">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4" >
          <h3>Collecté aujourd'hui: <?php echo $mass_input; ?> Kgs.</h3>
<?php
  if ($mass_input == "0") {
?>
<img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
<?php
} else {
?>
          <div id="graphj" style="height: 180px;"></div>
<?php
  //Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage des bilans de collecte en première page:
  if ($show_details) {
 ?>
          <a href=" bilanc.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=0" class="btn btn-default"  role="button">Détails &raquo;</a>
<?php
  }
}
?>
        </div>
        <div class="col-md-4">
        <h3>Evacué aujourd'hui: <?php echo $mass_output;?> Kgs.</h3>
<?php
  if ($mass_input == "0") {
?>
<img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
<?php
  } else {
?>
          <div id="grapha" style="height: 180px;"></div>
<?php
    //Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage des bilans de sortie hors-boutique en première page:
    if ($show_details) {
 ?>
          <a class="btn btn-default" href=" bilanhb.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>" role="button">Détails &raquo;</a>
<?php
    }
  }
?>
       </div>
        <div class="col-md-4">
          <h3>Vendu aujourd'hui: <?php echo $quantity_sell ?> Pcs.</h3>
<?php
  if ($quantity_sell == "0") {
?>
<img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
<?php
  } else {
?>
          <div id="graphm" style="height: 180px;"></div>
<?php
    //Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage des bilans de vente en première page:
    if ($show_details) {
?>
          <a class="btn btn-default" href=" bilanv.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>" role="button">Détails &raquo;</a>
<?php
    }
  }
?>
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
<?php
  // On recupère tout le contenu de la table affectations
  function export_data(PDOStatement $reponse) {
    $colors = array();
    $data = array();
      while ($result = $reponse->fetch()) {
      $colors[] = $result['color'];
      $data[] = array("label" => $result['label'], "value" => $result['value']);
    }
    $reponse->closeCursor(); // Termine le traitement de la requête
    return array('colors' => $colors, 'data' => $data);
  }

  $reponse = $bdd->query('
SELECT type_dechets.couleur color, type_dechets.nom label, sum(pesees_collectes.masse) value
FROM type_dechets, pesees_collectes
WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
GROUP BY nom');
  $json_j = json_encode(export_data($reponse));

  $reponse = $bdd->query(
    'SELECT type_dechets.couleur color, type_dechets.nom label, sum(vendus.quantite) value
    FROM type_dechets, vendus
    WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
    GROUP BY nom');
  $json_m = json_encode(export_data($reponse));

  $reponse = $bdd->query('SELECT type_dechets.couleur color,type_dechets.nom label, sum(pesees_sorties.masse) value
  FROM type_dechets,pesees_sorties
  WHERE type_dechets.id = pesees_sorties.id_type_dechet
  AND DATE(pesees_sorties.timestamp) = CURDATE()
  GROUP BY nom
  UNION
  SELECT types_poubelles.couleur color, types_poubelles.nom label, sum(pesees_sorties.masse) value
  FROM types_poubelles,pesees_sorties
  WHERE types_poubelles.id = pesees_sorties.id_type_poubelle
  AND DATE(pesees_sorties.timestamp) = CURDATE()
  GROUP BY nom
  UNION
  SELECT type_dechets_evac.couleur color, type_dechets_evac.nom label, sum(pesees_sorties.masse) value
  FROM type_dechets_evac ,pesees_sorties
  WHERE type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
  AND DATE(pesees_sorties.timestamp) = CURDATE()
  GROUP BY nom');
  $json_a = json_encode(export_data($reponse));
  // On affiche chaque entree une à une
?>
<script type="text/javascript">
  "use strict";

  window.onload = function() {
    const json_j = <?php echo($json_j); ?>;
    Morris.Donut({
    element: 'graphj',
      data: json_j.data,
      backgroundColor: '#ccc',
      labelColor: '#060',
      colors: json_j.colors,
      formatter: function (x) { return x + " Kg."}
    });

    const json_m = <?php echo($json_m); ?>;
    Morris.Donut({
    element: 'graphm',
      data: json_m.data,
      backgroundColor: '#ccc',
      labelColor: '#060',
      colors: json_m.colors,
      formatter: function (x) { return x + " pcs."}
    });

    const json_a = <?php echo($json_a); ?>;
    Morris.Donut({
    element: 'grapha',
      data: json_a.data,
      backgroundColor: '#ccc',
      labelColor: '#060',
      colors: json_a.colors,
      formatter: function (x) { return x + " Kg."}
    });

    setTimeout(function(){
      window.location.reload(true);
    }, 50000);
  };
</script>
<?php
  include "pied.php";
} else {
  header('Location: login.php') ;
}
?>

