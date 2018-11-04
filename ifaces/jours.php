<?php
/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

require_once '../core/requetes.php';
require_once '../core/session.php';
require_once '../core/composants.php';

function _generic_histo(PDO $bdd, string $table, string $field, int $type, string $debut, string $fin): array {
  $sql = "SELECT
      SUM($field) nombre,
      DATE_FORMAT(timestamp, '%Y-%m-%d') time
    FROM $table
    WHERE DATE($table.timestamp) BETWEEN :du AND :au
    AND $table.id_type_dechet = :type
    GROUP BY DATE_FORMAT(timestamp, '%Y-%m-%d')
    ORDER BY time";
  $stmt = $bdd->prepare($sql);
  $stmt->execute(['du' => $debut, 'au' => $fin, 'type' => $type]);
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  $sum = array_reduce($data, function (int $acc, array $e): int {
    return $acc + $e['nombre'];
  }, 0.0);
  $result = [
    'data' => $data,
    'sum' => $sum,
  ];
  return $result;
}

function moy_journa(array $data): float {
  return ($data['sum'] > 0) ? round($data['sum'] / count($data['data']),2) : 0;
}

function histogram(array $data, string $element, string $msg, string $type, string $unit) {
  $moy_jour = moy_journa($data);
  if ($moy_jour > 0) {
    ob_start();
    ?>
    <h3>Évolution de la <?= $msg ?> pour la catégorie <?= $type ?></h3>
    <p>Moyenne journalière: <?= $moy_jour ?> <?= $unit ?>.</p>
    <div id="<?= $element ?>" style="height: 180px;"></div>
    <?php
    return ob_get_clean();
  }
}

if (!(is_valid_session() && is_allowed_bilan())) {
  header('Location: ../moteur/destroy.php');
  die;
}

require_once '../moteur/dbconfig.php';

$types_dechets = types_dechets($bdd);
$type_selected = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT) ?? 1;
$type = array_values(array_filter($types_dechets, function ($e) use ($type_selected) {
      return $type_selected === $e['id'];
    }))[0];

$now =  new DateTimeImmutable();
$date1 = isset($_GET['date1']) ? DateTime::createFromFormat('d-m-Y', $_GET['date1']) : $now->sub(new DateInterval('P10D'));
$time_debut = $date1->format('Y-m-d') . " 00:00:00";
$start = $date1->format('d-m-Y');

$date2 = isset($_GET['date2']) ? DateTime::createFromFormat('d-m-Y', $_GET['date2']) : $now; 
$time_fin = $date2->format('Y-m-d') . " 00:00:00";
$end = $date2->format('d-m-Y');

$histoCol = _generic_histo($bdd, 'pesees_collectes', 'masse', $type_selected, $time_debut, $time_fin);
$histoSor = _generic_histo($bdd, 'pesees_sorties', 'masse', $type_selected, $time_debut, $time_fin);
$histoQV = _generic_histo($bdd, 'vendus', 'quantite', $type_selected, $time_debut, $time_fin);
$histoCA = _generic_histo($bdd, 'vendus', vendus_case_lot_unit(), $type_selected, $time_debut, $time_fin);
require_once 'tete.php';
?>

<div class="container">
  <div class="row">
    <div class="col-md-4">
      <?= datePicker() ?>
    </div>

    <div class="col-md-4">
      <label>Choisissez le type d'objet:</label>
      <br>
      <select name="select" onchange="location = this.value; return false;">
        <?php foreach ($types_dechets as $t) { ?>
          <option value="jours.php?date1=<?= $start ?>&date2=<?= $end ?>&type=<?= $t['id'] ?>"
                  <?= ($t['id'] === $type_selected) ? 'selected' : ''; ?>><?= $t['nom'] ?></option>
                <?php } ?>
      </select>
    </div>
  </div>

  <h2><?= $start === $end ? "Le $start " : "Du $start au $end" ?></h2>
  <?= histogram($histoCol, 'collectes', 'masse totale collectée', $type['nom'], 'Kg') ?>
  <?= histogram($histoSor, 'sorties', 'masses totales évacuées hors boutique', $type['nom'], 'Kg') ?>
  <?= histogram($histoQV, 'qv', 'quantités vendues', $type['nom'], 'Unités') ?>
  <?= histogram($histoCA, 'ca', 'chiffre de caisse quotidien', $type['nom'], '€') ?>
</div> <!-- .container -->

<script>
  // FIXME: Si les performances sont SI affreuses penser à utiliser un WebWorker.
  const collectes = <?= json_encode($histoCol, JSON_NUMERIC_CHECK) ?>;
  const sorties = <?= json_encode($histoSor, JSON_NUMERIC_CHECK) ?>;
  const quantite_vendue = <?= json_encode($histoQV, JSON_NUMERIC_CHECK) ?>;
  const chiffre_affaire = <?= json_encode($histoCA, JSON_NUMERIC_CHECK) ?>;
  const couleur = <?= json_encode($type['couleur']) ?>;

  document.addEventListener('DOMContentLoaded', () => {
    MorrisBar(collectes, 'collectes', 'Masse', ' Kg', couleur);
    MorrisBar(sorties, 'sorties', 'Masse', ' Kg', couleur);
    MorrisBar(quantite_vendue, 'qv', 'Qt.V', ' Unités', couleur);
    MorrisBar(chiffre_affaire, 'ca', "C.A", ' €', couleur);
  }, false);
</script>
<?php
require_once 'pied.php';
