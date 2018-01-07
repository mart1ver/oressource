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

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_users()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  $utilisateurs = utilisateurs($bdd);
  $info = [
    'text' => "Gestion des utilisateurs",
    'links' => [
      ['href' => 'utilisateurs.php', 'text' => 'Inscription'],
      ['href' => 'edition_utilisateurs.php', 'text' => 'Édition', 'state' => 'active']
    ]
  ];
  ?>

  <div class="container">
    <?= configNav($info) ?>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Mail</th>
          <th>Éditer</th>
          <th>Supprimer</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($utilisateurs as $u) { ?>
          <tr>
            <td><?= $u['id']; ?></td>
            <td><?= $u['nom']; ?></td>
            <td><?= $u['prenom']; ?></td>
            <td><?= $u['mail']; ?></td>
            <td>
              <a href="utilisateurs.php?id=<?= $u['id']; ?>" class="btn btn-warning btn-sm">Éditer</a>
            </td>
            <td>
              <form action="../moteur/sup_utilisateur.php" method="post">
                <input type="hidden" name="id" value="<?= $u['id']; ?>">
                <button class="btn btn-danger btn-sm">Supprimer</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
