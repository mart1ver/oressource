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

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

session_start();
if (is_valid_session() && is_allowed_config()) {
  require_once '../moteur/dbconfig.php';
  require_once 'tete.php';
  ?>
  <div class="container">
    <?= config_types3([
      'h1' => 'Gestion des moyens de paiement en caisse',
      'heading' => "Gérez ici les moyens de paiement disponibles aux différents points de vente",
      'text' => "Permet de définir les différents moyens de paiement disponibles aux différents points de vente.",
      'url' => '../moteur/moyens_paiement_post.php']) ?>

  <?= configModif(['data' => moyens_paiements($bdd), 'url' => 'moyens_paiement']) ?>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
