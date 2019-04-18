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

require_once('../moteur/dbconfig.php');
require_once('../core/session.php');
require_once('../core/requetes.php');

if (is_valid_session()) {
  require_once 'tete.php';

  $user = utilisateurs_id($bdd, $_SESSION['id']);
  ?>
  <div class="container">
    <h1>Édition de votre mot de passe:</h1>
    <p>Votre E-mail est: <?= $user['mail']; ?>, il vous est demandé au login.</p>
    <br>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/edition_mdp_utilisateur_post.php" method="post">
          <div class="col-md-3 col-md-offset-2">
            <div class="panel panel-default">
              <div class="panel-body">
                <label for="passold">Mot de passe actuel:</label>
                <input type="password"  name="passold" id="passold" class="form-control" required >
                <br>
                <label for="pass1">Nouveau mot de passe:</label>
                <input type="password" name="pass1" id="pass1" class="form-control" required >
                <br>
                <label for="pass2">Répétez le nouveau mot de passe:</label>
                <input type="password" name="pass2" id="pass2" class="form-control" required >
                <br>
                <button name="creer" class="btn btn-danger">Modifier!</button>
                </form>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
