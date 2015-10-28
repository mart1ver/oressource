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
  /*
   * On recupére les quantitées vendues, et masses entrées et sorties.
   * La requête est pas très belle. Mais c'est mieux que trois separées...
   */
  $reponse = $bdd->query('
  SELECT COALESCE(SUM(vendus.quantite), 0) as x
  FROM vendus
  WHERE DATE(vendus.timestamp) = CURDATE()
    AND vendus.remboursement = 0
  UNION ALL
  SELECT SUM(pesees_collectes.masse)
  FROM pesees_collectes
  WHERE DATE(pesees_collectes.timestamp) = CURDATE()
  UNION ALL
  SELECT COALESCE(SUM(pesees_sorties.masse), 0)
  FROM pesees_sorties
  WHERE DATE(pesees_sorties.timestamp) = CURDATE()
');

  $donnees = $reponse->fetchAll();
  $quantity_sell = $donnees[0]['x'];
  $mass_input = $donnees[1]['x'];
  $mass_output = $donnees[2]['x'];
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
          <div class="chart" id="chart_inputs"></div>
          <a hidden
             class="charts-details btn btn-default"
             role="button"
             href="/ifaces/bilanc.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=0"
          >Détails &raquo;</a>
        </div>
        <div class="col-md-4">
        <h3>Evacué aujourd'hui: <?php echo $mass_output;?> Kgs.</h3>
        <div class="chart" id="chart_outputs"></div>
          <a hidden
             class="chart-details btn btn-default"
             role="button"
             href="/ifaces/bilanhb.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=0"
           >Détails &raquo;</a>
       </div>
       <div class="col-md-4">
         <h3>Vendu aujourd'hui: <?php echo $quantity_sell ?> Pcs.</h3>
          <div class="chart" id="chart_sells"></div>
          <a hidden
             role="button"
             class="chart-details btn btn-default"
             href="/ifaces/bilanv.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=0"
          >Détails &raquo;</a>
        </div>
      </div>
     </div> <!-- /container -->
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
  $json_inputs = json_encode(export_data($reponse));

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
  $json_outputs = json_encode(export_data($reponse));

  $reponse = $bdd->query(
    'SELECT type_dechets.couleur color, type_dechets.nom label, sum(vendus.quantite) value
    FROM type_dechets, vendus
    WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
    GROUP BY nom');
  $json_sells = json_encode(export_data($reponse));
?>
  <script type="text/javascript">
  "use strict";

  window.onload = function() {
    function make_chart(id, json, units) {
      const len_data = Object.keys(json.data).length;
      const len_colors = Object.keys(json.data).length;
      if (len_data != 0 && len_colors != 0 ) {
        Morris.Donut({
        element: id,
          data: json.data,
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: json.colors,
          formatter: function (x) { return x + units; }
      });
      } else {
        // fetch is a standard on top of XHttpRequest.
        fetch('/images/nodata.svg')
          .then(function(response) {
            return response.text()
          }).then(function(raw_svg) {
            // Somewhat complicated by it work.
            const P = new DOMParser();
            const svg = P.parseFromString(raw_svg, "image/svg+xml");
            const importedSVG = document.importNode(svg.documentElement, true);
            const div = document.getElementById(id);
            div.appendChild(importedSVG);

          }).catch(function(ex) {
            console.log(ex)
          });
      }
    }

    const json_inputs = <?php echo($json_inputs); ?>;
    make_chart('chart_inputs', json_inputs, "Kg.");

    const json_outputs = <?php echo($json_outputs); ?>;
    make_chart('chart_outputs', json_outputs, "Kg.");

    const json_sells = <?php echo($json_sells); ?>;
    make_chart('chart_sells', json_sells, "pcs.");

    const show_details = <?php echo(json_encode($show_details));?>;
    if (show_details) {
      const details = document.getElementsByClassName('chart-details');
      console.log(details);
      for (var e of details) {
        e.setAttribute('hidden', false);
      }
    };

    setTimeout(function() {
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

