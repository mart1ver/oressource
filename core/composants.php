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

function textInput(array $props, string $state) {
  ob_start();
  ?>
  <label><?= $props['text'] ?>
    <input type="text"
           name="<?= $props['name'] ?>"
           id="<?= $props['name'] ?>"
           value="<?= $state ?>"
           class="form-control" required autofocus>
  </label>
  <?php
  return ob_get_clean();
}

function mailInput(array $props, string $state) {
  ob_start();
  ?>
  <label>Mail:
    <input type="email"
           value="<?= $state ?>"
           name="<?= $props['name'] ?>"
           id="<?= $props['name'] ?>"
           class="form-control"
           required>
  </label>
  <?php
  return ob_get_clean();
}

function linkNav(array $props) {
  ob_start();
  ?>
  <li class="<?= $props['state'] ?? '' ?>">
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
