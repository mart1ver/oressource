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

require_once('../core/requetes.php');
require_once('../core/session.php');
require_once('../moteur/dbconfig.php');
require_once('../core/composants.php');

global $bdd;

$now_date = (new DateTime())->format('d-m-Y');
$can_gestion = is_allowed_gestion();
$can_verif = is_allowed_verifications();
$can_users = is_allowed_users();
$can_parners = is_allowed_partners();
$can_config = is_allowed_config();

// FIXME: mostly a fix of
$nav = filter_visibles(new_nav_sorties());

$activeTab = explode('/ifaces/', $_SERVER['PHP_SELF'])[1];
if (array_key_exists('QUERY_STRING', $_SERVER) && strpos($_SERVER['QUERY_STRING'], 'numero=') !== false) {
  $activeNumber = explode('numero=', $_SERVER['QUERY_STRING'])[1];
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../images/favicon.ico">
    <title>Oressource</title>
    <link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="../css/bootstrap-switch.css" rel="stylesheet">
    <link href="../css/oressource.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../js/morris/morris.css">
    <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker-bs3.css">
    <script src="../js/jquery-2.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-switch.js"></script>
    <script src="../js/raphael.js"></script>
    <script src="../js/morris/morris.min.js"></script>
    <script src="../js/moment.js"></script>
    <script src="../js/fr.js"></script>
    <script src="../js/daterangepicker.js"></script>
    <script src="../js/utils.js"></script>
    <script src="../js/ticket.js"></script>
    <script src="../js/numpad.js"></script>
  </head>

  <body>
    <nav id="nav_main" class="navbar container" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../ifaces/index.php"><img height=40 src="../images/logo_oressource.png" class="hidden-md hidden-sm hidden-xs"><img src="../images/logo_or.png" class="hidden-lg"></a>
        </div>

        <div class="navbar-collapse collapse navbar-right">
          <ul class="nav navbar-nav">

            <?php if (is_allowed_vente()):?>
              <?php if (count(filter_visibles(points_ventes($bdd))) < 2):?>
                <li class="<?php if ($activeTab == 'ventes.php'): ?>active<?php endif; ?>">
                  <a href="../ifaces/ventes.php?numero=<?php echo filter_visibles(points_ventes($bdd))[0]['id'] ?>">Point de vente</a>
                </li>
              <?php else: ?>
              <li class="nav navbar-nav dropdown<?php if ($activeTab == 'ventes.php'): ?> active<?php endif; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="position: relative;">Points de vente<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <?php foreach (filter_visibles(points_ventes($bdd)) as $point_vente): ?>
                      <?php if (is_allowed_vente_id($point_vente['id'])): ?>
                        <li class="<?php if ($activeTab == 'ventes.php' && $point_vente['id'] == $activeNumber):?>active<?php endif;?>">
                          <a href="../ifaces/ventes.php?numero=<?php echo "{$point_vente['id']}"; ?>"
                            ><?php echo $point_vente['nom']; ?></a>
                        </li>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif;?>
                </li>
              <?php endif; ?>


            <?php if (is_allowed_collecte()): ?>
              <?php if (count(filter_visibles(points_collectes($bdd))) < 2): ?>
                <li class="<?php if ($activeTab == 'collecte.php'): ?>active<?php endif; ?>">
                  <a href="../ifaces/collecte.php?numero=<?php echo filter_visibles(points_collectes($bdd))[0]['id'] ?>">Point de collecte</a>
                </li>
              <?php else: ?>
              <li class="nav navbar-nav dropdown<?php if ($activeTab == 'ventes.php'): ?> active<?php endif; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Points de collecte<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <?php foreach (filter_visibles(points_collectes($bdd)) as $point_collecte): ?>
                    <?php if (is_allowed_collecte_id($point_collecte['id'])): ?>
                      <li class="<?php if ($activeTab == 'ventes.php' && $point_collecte['id'] == $activeNumber):?>active<?php endif;?>">
                        <a href="../ifaces/collecte.php?numero=<?= "{$point_collecte['id']}"; ?>"><?= $point_collecte['nom']; ?></a>
                      </li>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              </li>
            <?php endif; ?>

            <?php if (is_allowed_sortie() && count($nav)): ?>
              <?php if (count(filter_visibles(points_sorties($bdd))) < 2): ?>
                <li class="<?php if (preg_match('/sorties[pcrd]?\.php/', $activeTab)): ?>active<?php endif; ?>">
                  <a href="<?php echo $nav[0]['href'] ?>?numero=<?php echo filter_visibles(points_sorties($bdd))[0]['id'] ?>">Sortie hors-boutique</a>
                </li>
              <?php else: ?>
                <li class="nav navbar-nav dropdown<?php if (preg_match('/sorties[pcrd]?\.php/', $activeTab)): ?> active<?php endif; ?>">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sorties hors-boutique<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <?php foreach (filter_visibles(points_sorties($bdd)) as $point_sortie): ?>
                      <?php if (is_allowed_sortie_id($point_sortie['id'])): ?>
                        <li class="<?php if (preg_match('/sorties[pcrd]?\.php/', $activeTab) && $point_sortie['id'] == $activeNumber):?>active<?php endif;?>">
                          <a href="<?php echo "{$nav[0]['href']}?numero={$point_sortie['id']}" ?>"><?php echo $point_sortie['nom']; ?></a>
                        </li>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </li>
            <?php endif; ?>

            <?php if (is_allowed_bilan()): ?>
              <li class="<?php if (in_array($activeTab , ['recapitulatif.php', 'bilanc.php', 'bilanhb.php', 'bilanv.php'])): ?>active<?php endif;?>">
                <a href="../ifaces/recapitulatif.php"><span class="glyphicon glyphicon-stats"></span>&nbsp;Bilans</a>
              </li>
            <?php endif; ?>

            <?php if ($can_gestion || $can_verif || $can_users || $can_parners || $can_config): ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span>&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <!-- Grille des prix et masse des bacs(gestion quotidienne) -->
                  <?php if ($can_gestion): ?>
                    <li class="<?php if ($activeTab == 'grilles_prix.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/grilles_prix.php?typo=1">Grille des prix</a>
                    </li>
                    <li class="<?php if ($activeTab == 'edition_types_contenants.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/edition_types_contenants.php">Bacs et chariots</a>
                    </li>
                    <li class="<?php if ($activeTab == 'edition_types_poubelles.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/edition_types_poubelles.php">Types de poubelles</a>
                    </li>
                    <li class="divider"></li>
                  <?php endif; ?>

                  <!-- Gestion et verification -->
                  <?php if ($can_verif): ?>
                    <li class="<?php if ($activeTab == 'verif_collecte.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/verif_collecte.php?date1=<?= $now_date; ?>&date2=<?= $now_date; ?>&numero=1">Vérifier les collectes</a>
                    </li>
                    <li class="<?php if ($activeTab == 'verif_sorties.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/verif_sorties.php?date1=<?= $now_date; ?>&date2=<?= $now_date; ?>&numero=1">Vérifier les sorties hors-boutique</a>
                    </li>
                    <li class="<?php if ($activeTab == 'verif_vente.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/verif_vente.php?date1=<?= $now_date; ?>&date2=<?= $now_date; ?>&numero=1">Vérifier les ventes</a>
                    </li>
                    <li class="divider"></li>
                  <?php endif; ?>

                  <!-- Utilisateurs -->
                  <?php if ($can_users): ?>
                    <li class="<?php if ($activeTab == 'utilisateurs.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/utilisateurs.php">Utilisateurs</a>
                    </li>
                    <li class="divider"></li>
                  <?php endif; ?>

                  <!-- Recycleur et conventions de sortie -->
                  <?php if ($can_parners): ?>
                    <li class="<?php if ($activeTab == 'edition_filieres_sortie.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/edition_filieres_sortie.php">Entreprises de recyclage</a>
                    </li>
                    <li class="<?php if ($activeTab == 'conventions_sortie.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/conventions_sortie.php">Conventions avec les partenaires</a>
                    </li>
                    <li class="divider"></li>
                  <?php endif; ?>

                  <!-- Configuration -->
                  <?php if ($can_config): ?>
                    <li class="<?php if ($activeTab == 'types_sortie.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/types_sortie.php">Types de sorties hors-boutique</a>
                    </li>
                    <li class="<?php if ($activeTab == 'types_collecte.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/types_collecte.php">Types de collectes</a>
                    </li>

                    <li class="divider"></li>

                    <li class="<?php if ($activeTab == 'types_dechets.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/types_dechets.php">Types d'objets collectés</a>
                    </li>
                    <li class="<?php if ($activeTab == 'types_dechets_evac.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/types_dechets_evac.php">Types de déchets evacués</a>
                    </li>

                    <li class="divider"></li>

                    <li class="<?php if ($activeTab == 'edition_points_collecte.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/edition_points_collecte.php">Points de collecte</a>
                    </li>
                    <li class="<?php if ($activeTab == 'edition_points_sorties.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/edition_points_sorties.php">Points de sortie hors-boutique</a>
                    </li>
                    <li class="<?php if ($activeTab == 'edition_points_vente.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/edition_points_vente.php">Points de vente</a>
                    </li>

                    <li class="divider"></li>
                    <li class="<?php if ($activeTab == 'moyens_paiment.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/moyens_paiment.php">Moyens de paiment</a>
                    </li>
                    <li class="<?php if ($activeTab == 'edition_localites.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/edition_localites.php">Localités</a>
                    </li>
                    <li class="<?php if ($activeTab == 'structures.php'): ?>active<?php endif;?>">
                      <a href="../ifaces/structures.php">Configuration de Oressource</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (is_valid_session()): ?>
              <li class="dropdown<?php if($activeTab == 'edition_mdp_utilisateur.php'): ?> active<?php endif; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="<?php if($activeTab == 'edition_mdp_utilisateur.php'): ?>active<?php endif; ?>" href="../ifaces/edition_mdp_utilisateur.php">Mot de passe</a>
                  </li>
                  <li>
                    <a href="../moteur/destroy.php">Déconnexion</a>
                  </li>
                </ul>
              </li>
            <?php endif; ?>

            <?php if (isset($menu_extra)): ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">❤️&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <?php foreach ($menu_extra as $page => $lien): ?>
                    <li>
                      <a href="<?php echo $lien ?>" target="_blank"><?php echo $page; ?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <?php if (isset($_GET['err'])): ?>
      <div class='alert alert-danger' style='width:80%;margin:auto;'>
        <p><?= $_GET['err']; ?></p>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg'])): ?>
      <div class='alert alert-success alert-dismissable' style='width:80%;margin:auto;'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <p><?= $_GET['msg']; ?></p>
      </div>
    <?php endif;?>

    <div id="main" class="container">
