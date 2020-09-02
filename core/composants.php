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

require_once '../core/validation.php';

/* Fonction utile pour la nav des sorties. */


function new_nav_sorties(): array {
  $links = [
    ['visible' => affichage_sortie_poubelle(), 'href' => "sortiesp.php", 'text' => 'Poubelles'],
    ['visible' => affichage_sortie_partenaires(), 'href' => "sortiesc.php", 'text' => 'Sorties partenaires'],
    ['visible' => affichage_sortie_recyclage(), 'href' => "sortiesr.php", 'text' => 'Recyclage'],
    ['visible' => affichage_sortie_don(), 'href' => "sorties.php", 'text' => 'Dons'],
    ['visible' => affichage_sortie_dechetterie(), 'href' => "sortiesd.php", 'text' => 'Déchetterie'],
  ];

  // Don't keep not visibles sorties first remove invisible,
  // Then throw away the boolean.
  return $links;
}

// ⚠ FIXME ⚠ this function is really fragile due to the way we manage
// sortie this needs a complete rewrite.
// Rewrited to fix: https://github.com/mart1ver/oressource/issues/370
function new_nav(string $text, int $numero, int $active): array {
  $links = new_nav_sorties();
  // add numero in url fragment
  $links = array_map(function (array $e) use ($numero) {
    $e['href'] .= "?numero={$numero}";
    return $e;
  }, $links);

  // WARNING: A hack to get the active.
  $links[$active]['state'] = true;

  // Filter visibles
  $links = array_values(array_filter($links, function (array $e) {
    return $e['visible'];
  }));

  $nav = [
    'text' => $text,
    'links' => $links
  ];
  return $nav;
}

function checkBox(array $props, bool $state) {
  ob_start();
  ?>
  <div class="checkbox">
    <label>
      <input
        name="<?= $props['name'] ?>"
        id="<?= $props['name'] ?>"
        <?= $state ? 'checked' : '' ?>
        value="<?= json_encode($state) ?>"
        type="checkbox">
        <?= $props['text'] ?>
    </label>
  </div>
  <?php
  return ob_get_clean();
}

function configCheckboxArea(array $props) {
  ob_start();
  ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title"><?= $props['text'] ?></h3>
    </div>
    <div class="panel-body form-group custom-controls-stacked">
      <?php foreach ($props['data'] as $data) { ?>
        <?= checkBox($data[0], $data[1]) ?>
      <?php } ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

function linkNav(array $props) {
  ob_start();
  ?>
  <li class="<?= ($props['state'] ?? false) ? 'active' : '' ?>">
    <a href="<?= $props['href'] ?>"><?= $props['text'] ?></a>
  </li>
  <?php
  return ob_get_clean();
}

function configNav(array $props) {
  ob_start();
  ?>
  <nav class="navbar">
    <div class="header-header">
      <h1><?= $props['text'] ?></h1>
    </div>
    <ul class="nav nav-tabs">
      <?php foreach ($props['links'] as $link) { ?>
        <?= linkNav($link) ?>
      <?php } ?>
    </ul>
  </nav>
  <?php
  return ob_get_clean();
}

function configInfo(array $props) {
  ob_start();
  ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title">Informations</h3>
    </div>
    <div class = "panel-body">
      <?= textInput(['name' => 'nom', 'text' => "Nom:"], $props['nom']) ?>
      <?= textInput(['name' => 'prenom', 'text' => "Prénom:"], $props['prenom']) ?>
      <?= mailInput(['name' => 'mail', 'text' => "Courriel:"], $props['mail']) ?>
      <?php if ($props['type'] === 'edit') { ?>
        <a href="edition_mdp_admin.php?id=<?= $props['id']; ?>&mail=<?= $props['mail'] ?>">
          <button name="creer" type="button" class="btn btn btn-danger">Changer le mot de passe</button>
        </a>
      <?php } else { ?>
        <label>Mot de passe
          <input type="password" name="pass1" id="pass1" class="form-control" required>
        </label>
        <label>Répetez le mot de passe
          <input type="password" name="pass2" id="pass2" class="form-control" required>
        </label>
      <?php } ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

function buttonCollectesSorties() {
  ob_start();
  ?>
  <div class="btn-group" role="group">
    <button id="encaissement" class="btn btn-success btn-lg">C'est pesé!</button>
    <button id="impression" class="btn btn-primary btn-lg" value="Print"><span class="glyphicon glyphicon-print"></span></button>
  </div>
  <?php
  return ob_get_clean();
}

/*
 * Il ne faut pas changer les id, ni les noms des inputs de ce composant sans modifier
 * le javascript associée.
 */
function cartList(array $props) {
  ob_start();
  ?>
  <div class="col-md-4">
    <div id="ticket" class="panel panel-info" >
      <div class="panel-heading">
        <h3 class="panel-title">
          <label id="massetot"><?= $props['text'] ?></label>
        </h3>
      </div>
      <div class="panel-body">
        <form id="formulaire" method="POST">
          <?php if (is_allowed_saisie_date() && is_allowed_edit_date()) { ?>
            <div>
              <label for="antidate">Date:</label>
              <input type="date" id="antidate" name="date"
                     value="<?= $props['date'] ?>">
            </div>
          <?php } ?>
          <ul class="list-group" id="transaction">
            <!-- Filled by Javascript -->
          </ul>
        </form>
      </div>
      <div class="panel-footer">
        <input type="text" form="formulaire" class="form-control"
               name="commentaire" id="commentaire" placeholder="Commentaire">
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

function bilanTableHeader3(array $props) {
  ob_start();
  ?>
  <tr>
    <th style="width:300px"><?= $props['td0'] ?></th>
    <th><?= $props['td1'] ?></th>
    <th><?= $props['td2'] ?></th>
  </tr>
  <?php
  return ob_get_clean();
}

function bilanTableBody3(array $props) {
  ob_start();
  ?>
  <tbody>
    <?php foreach ($props['data'] as $data) { ?>
      <tr>
        <td><?= $data['nom']; ?></td>
        <td><?= $data['somme']; ?></td>
        <td><?= round($data['somme'] * 100 / $props['masse'], 2); ?></td>
      </tr>
    <?php } ?>
  </tbody>
  <?php
  return ob_get_clean();
}

function bilanTable3Hover(array $props) {
  ?>
  <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
    <thead>
    <th style="width:300px"><?= $props['text'] ?></th>
    <thead>
      <?= bilanTableHeader3($props) ?>
    </thead>
    <?= bilanTableBody3($props) ?>
  </table>
  <?php
}

function bilanTable3(array $props) {
  ob_start();
  ?>
  <div class="list-group">
    <a class="list-group-item" data-toggle="collapse"
       href="#collapse<?= $props['id'] ?>" aria-expanded="false"
       aria-controls="collapse<?= $props['id'] ?>"><?= $props['text'] ?></a>
  </div>
  <div class="collapse" id="collapse<?= $props['id'] ?>">
    <table class="table table-condensed s
           table-striped table
           table-bordered table-hover" style="border-collapse:collapse;">
      <thead>
        <?= bilanTableHeader3($props) ?>
      </thead>
      <?= bilanTableBody3($props) ?>
    </table>
  </div>
  <?php
  return ob_get_clean();
}

/*
 * Attention le champ `key` de `$props` sera utilisé par le JavaScript pour
 * remplir la liste.
 */
function listSaisie(array $props) {
  ob_start();
  ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title">
        <label><?= $props['text'] ?></label>
      </h3>
    </div>
    <div class="panel-body">
      <div id="<?= $props['key'] ?>" class="btn-group" >
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

function config_types3(array $props): string {
  return config_types3_param(array_merge($props, [
    'nom' => $_GET['nom'] ?? '',
    'commentaire' => $_GET['description'] ?? '',
    'couleur' => $_GET['couleur'] ?? ''
  ]));
}

function config_types3_header(array $props): string {
  ob_start();
  ?>
  <h1><?= $props['h1'] ?></h1>
  <div class="panel-heading"><?= $props['heading'] ?></div>
  <p><?= $props['text'] ?></p>

  <?php
  return ob_get_clean();
}

function config_types3_form(array $props): string {
  ob_start();
  ?>
  <div class="row">
    <form action="../moteur/<?= $props['url'] ?>_post.php" method="post">
      <div class="col-md-3"><?= textInput(['name' => 'nom', 'text' => "Nom:"], $props['nom']) ?></div>
      <div class="col-md-4"><?= textInput(['name' => 'commentaire', 'text' => "Description:"], $props['commentaire']) ?></div>
      <div class="col-md-1"><label for="couleur">Couleur:</label><input id="couleur" name="couleur" type="color" value="#<?= $props['couleur'] ?>" class="form-control" required></div>
      <div class="col-md-1"><br><button class="btn btn-default">Créer</button></div>
    </form>
  </div>
  <?php
  return ob_get_clean();
}

function config_types3_page_modif(array $props): string {
  global $_SERVER;
  ob_start();
  ?>
 <div class="container">
    <h1><?= $props['h1'] ?></h1>
    <div class="panel-heading">Modifier les données concernant le <?= $props['type'] ?> n°<?= $props['id'] ?>, <?= $props['nom'] ?>.</div>
    <div class="panel-body">
      <div class="row">
        <?= config_types3_form_modif($props) ?>
        <br>
        <a href="<?= $_SERVER['HTTP_REFERER'] ?>">
          <button class="btn btn">Annuler</button>
        </a>
      </div>
    </div>
  </div><!-- /.container -->
  <?php
  return ob_get_clean();
}

function config_types3_form_modif(array $props): string {
  ob_start();
  ?>
  <form action="../moteur/modification_<?= $props['endpoint'] ?>_post.php" method="post">
    <input type="hidden" name="id" value="<?= $props['id'] ?>">
    <div class="col-md-3"><?= textInput(['name' => 'nom', 'text' => "Nom:"], $props['nom']) ?></div>
    <div class="col-md-4"><?= textInput(['name' => 'description', 'text' => "Description:"], $props['description']) ?></div>
    <div class="col-md-1"><label for="couleur">Couleur:</label><input id="couleur" name="couleur" type="color" value="<?= $props['couleur'] ?>" class="form-control" required></div>
    <div class="col-md-1"><br><button class="btn btn-default">Modifier</button></div>
  </form>
  <?php
  return ob_get_clean();
}


function config_types4_form(array $props): string {
  ob_start();
  ?>
  <div class="row">
    <form action="../moteur/<?= $props['url'] ?>_post.php" method="post">
      <input type="hidden" name="id" value="<?= $props['id'] ?? '' ?>">
      <div class="col-md-2"><?= textInput(['name' => 'nom', 'text' => "Nom:"], $props['nom']) ?></div>
      <div class="col-md-3"><?= textInput(['name' => 'description', 'text' => "Description:"], $props['description']) ?></div>
      <div class="col-md-2"><?= textInput(['name' => 'masse', 'text' => "Masse en (Kg):"], $props['masse']) ?></div>
      <div class="col-md-1"><label for="couleur">Couleur:</label><input id="couleur" name="couleur" type="color" value="#<?= $props['couleur'] ?>" class="form-control" required></div>
      <div class="col-md-1"><br><button class="btn btn-default"><?= $props['textBtn'] ?></button></div>
    </form>
  </div>
  <?php
  return ob_get_clean();
}

/*
 * A utiliser dans les configurations qui neccessitent les champs nom, description, couleur.
 */
function config_types3_param(array $props): string {
  ob_start();
  ?>
  <?= config_types3_header($props) ?>
  <div class="container">
    <div class="panel-body">
      <?= config_types3_form($props) ?>
    </div>
    <?= configModif($props) ?>
  </div>
  <?php
  return ob_get_clean();
}

function configBtnVisible(array $props): string {
  ob_start();
  ?>
  <form action="../moteur/visible.php" method="post">
    <input type="hidden" name="id" value="<?= $props['id']; ?>"/>
    <input type="hidden" name="table" value="<?= $props['url'] ?>"/>
    <input type="hidden" name="visible" value="<?= json_encode(!((bool)$props['visible'])) ?>"/>
    <button class="btn btn-info btn-sm <?= $props['visible'] ? 'btn-info' : 'btn-danger' ?>"><?= bool_to_oui_non($props['visible']) ?></button>
  </form>
  <?php
  return ob_get_clean();
}

function configModif(array $props): string {
  ob_start();
  ?>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Date de création</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Couleur</th>
        <th>Visible</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($props['data'] as $e) { ?>
        <tr>
          <td><?= $e['id']; ?></td>
          <td><?= $e['timestamp']; ?></td>
          <td><?= $e['nom']; ?></td>
          <td><?= $e['description']; ?></td>
          <td><span class="badge" style="background-color:<?= $e['couleur']; ?>"><?= $e['couleur']; ?></span></td>
          <td><?= configBtnVisible(['url' => $props['url'], 'id' => $e['id'], 'visible' => $e['visible']]) ?></td>
          <td>
            <form action="modification_<?= $props['url'] ?>.php" method="post">
              <input type="hidden" name="id" id="id" value="<?= $e['id']; ?>">
              <input type="hidden" name="nom" id="nom" value="<?= $e['nom']; ?>">
              <input type="hidden" name="description" id="description" value="<?= $e['description']; ?>">
              <input type="hidden" name="couleur" id="couleur" value="<?= substr($e['couleur'], 1); ?>">
              <button class="btn btn-warning btn-sm">Modifier</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php
  return ob_get_clean();
}

// Hack resolve problem in production with sorties_partenaires
function page_config3(array $props, callable $droits) {
  require_once 'session.php';
  require_once 'requetes.php';

  session_start();

  if (is_valid_session() && $droits()) {
    global $bdd;
    ob_start();
    require_once 'tete.php';
    $props['data'] = $props['functData']($bdd);
    ?>
    <div class="container">
      <?= config_types3($props) ?>
    </div><!-- /.container -->
    <?php
    require_once 'pied.php';
    return ob_get_clean();
  } else {
    header('Location: ../moteur/destroy.php');
  }
}

function datePicker($props=[]): string {
  ob_start();
  ?>
  <div class="<?= $props['css'] ?? '' ?> pull-right">
      <label for="reportrange">Choisissez la période à inspecter:</label><br>
      <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
        <i class="fa fa-calendar"></i>
        <span></span> <b class="caret"></b>
      </div>
    <script>
      'use strict';
      const query = processGet();
      const base = new URL(location.href);
      const options = setDatepicker(query);
      bindDatepicker(options, { base: base.pathname, query });
    </script>
  </div>
  <?php
  return ob_get_clean();
}

function headerVerif(array $props): string {
  ob_start();
  ?>
  <h1><?= $props['h1'] ?></h1>
  <div class="panel-body">
    <p><?= ($props['start'] === $props['end'] ? "Le " : "Du {$props['start']} au ") . $props['end'] ?> :</p>
    <ul class="nav nav-tabs">
      <?php foreach ($props['points'] as $d) { ?>
        <li class="<?= $props['numero'] === $d['id'] ? 'active' : '' ?>">
          <a href="<?= $props['endpoint'] ?>.php?numero=<?= $d['id'] ?>&date1=<?= $props['start'] ?>&date2=<?= $props['end'] ?>"><?= $d['nom']; ?></a>
        </li>
        <?php
      }
      ?>
    </ul>
    <br>
    <?= datePicker(['css' => 'row']) ?>
  </div>
  <?php
  return ob_get_clean();
}

function tableVerif(array $props): string {
  $users = $props['users'];
  ob_start();
  ?>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Crée le</th>
        <th><?= $props['th1'] ?></th>
        <th>Commentaire</th>
        <th><?= $props['th3'] ?></th>
        <th><?= $props['th4'] ?></th>
        <th>Créée par</th>
        <th></th>
        <th>Modifié par</th>
        <th>Le</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($props['data'] as $d) { ?>
        <tr>
          <td style="height:20px"><?= $d['id']; ?></td>
          <td style="height:20px"><?= $d['timestamp']; ?></td>
          <td style="height:20px"><?= $d['nom']; ?></td>
          <td style="height:20px"><?= $d['commentaire']; ?></td>
          <td style="height:20px"><?= $d['localisation']; ?></td>
          <td style="height:20px"><?= $d['masse']; ?></td>
          <td><?= $users[$d['id_createur']]['mail']; ?></td>
          <td><a style="appearance: button" class="btn btn-warning btn-sm" href="../ifaces/modification_<?= $props['endpoint'] ?>.php?id=<?= $d['id'] ?>">Modifier</a></td>
          <td><?= $d['lht'] !== $d['timestamp'] ? $users[$d['id_last_hero']]['mail'] : '' ?></td>
          <td><?= $d['lht'] !== $d['timestamp'] ? $d['lht'] : '' ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php
  return ob_get_clean();
}

function selectConfig(array $props): string {
  ob_start();
  ?>
  <div class="col-md-3">
    <label for="<?= $props['key']; ?>"><?= $props['text']; ?></label>
      <select name="<?= $props['key']; ?>" id="<?= $props['key']; ?>" class="form-control" required>
      <?php foreach ($props['data'] as $d) { ?>
        <option value="<?= $d['id']; ?>" <?= $props['active'] === $d['id'] ? 'selected' : '' ?>><?= $d['nom']; ?></option>
      <?php } ?>
    </select>
  </div>
  <?php
  return ob_get_clean();
}

function formModif(array $props): string {
ob_start();
$classe = $props['classe'] ?? '';
?>
<form action="modification_<?= $props['endpoint'] ?>.php" method="post">
  <input type="hidden" name="id" value="<?= $props['id'] ?>">
  <input type="hidden" name="id_type" value="<?= $props['id_type'] ?? 0 ?>">
  <input type="hidden" name="classe" value="<?= $classe ?>">
  <input type="hidden" name="commentaire" value="<?= $props['commentaire'] ?? '' ?>">
  <input type="hidden" name="date1" value="<?= $props['start'] ?>">
  <input type="hidden" name="date2" value="<?= $props['end'] ?>">
  <input type="hidden" name="npoint" value="<?= $props['numero'] ?>">
  <button class="btn btn-warning btn-sm">Modifier</button>
</form>
<?php
return ob_get_clean();
}

function listPesees(array $props): string {
  ob_start();
  $users = $props['users'];
  ?>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Type de déchet:</th>
        <th>Masse</th>
        <th>Auteur de la ligne</th>
        <th></th>
        <th>Modifié par</th>
        <th>Le:</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($props['data'] as $d) { ?>
        <tr>
          <td><?= $d['id']; ?></td>
          <td><?= $d['timestamp']; ?></td>
          <td><span class="badge" id="cool" style="background-color:<?= $d['couleur']; ?>"><?= $d['nom']; ?></span></td>
          <td><?= $d['masse']; ?></td>
          <td><?= $users[$d['id_createur']]['mail']; ?></td>
          <td><a style="appearance: button" class="btn btn-warning btn-sm" href="../ifaces/modification_<?= $props['endpoint'] ?>.php?id=<?= $d['id'] ?>">Modifier</a></td>
          <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $users[$d['id_last_hero']]['mail'] : '' ?></td>
          <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $d['last_hero_timestamp'] : '' ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php
  return ob_get_clean();
}

function textInput(array $props, string $state): string {
  ob_start();
  ?>
  <label for="<?= $props['name'] ?>"><?= $props['text'] ?></label>
  <input type="text"
         name="<?= $props['name'] ?>"
         id="<?= $props['name'] ?>"
         value="<?= $state ?>"
         class="form-control"
         autocomplete="off"
         required/>
    <?php
    return ob_get_clean();
  }

function mailInput(array $props, string $state): string {
  ob_start();
  ?>
  <label for="<?= $props['name'] ?>">Courriel :</label>
  <input type="email"
         value="<?= $state ?>"
         name="<?= $props['name'] ?>"
         id="<?= $props['name'] ?>"
         class="form-control"
         autocomplete="off"
         required/>
  <?php
  return ob_get_clean();
}
