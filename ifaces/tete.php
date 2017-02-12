<?php
//session_start(); déjà inclu dans chacun des fichiers appelant ce fichier
require_once('../moteur/dbconfig.php');
require_once('../core/session.php');
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
    <link href="../css/oressource.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../js/morris/morris.css">
    <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker-bs3.css" />
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../">Oressource</a>
        </div>
        <div class="navbar-collapse collapse  navbar-right">
          <ul class="nav navbar-nav">

            <?php
            if (is_valid_session()) {
              if (is_allowed_collecte()) {
                ?>
                <li class="nav navbar-nav dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Points de collecte<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <?php
                    // On recupère tout le contenu de la table point de collecte
                    $stmt = $bdd->query('SELECT id, nom, adresse, visible FROM points_collecte');
                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $point_collecte) {
                      if (is_collecte_visible($point_collecte)) {
                        $nom = $point_collecte['nom'];
                        $url_query = "{$point_collecte['id']}&nom={$nom}&adresse={$point_collecte['adresse']}";
                        ?>
                        <li>
                          <a href="../ifaces/collecte.php?numero=<?php echo $url_query; ?>"><?php echo $nom; ?></a>
                        </li>
                        <?php
                      }
                    }
                    ?>
                  </ul>
                </li>
                <?php
              }

              if (is_allowed_sortie()) {
                ?>
                <li class="nav navbar-nav dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sorties hors-boutique<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <?php
                    // On recupère tout le contenu de la table point de vente
                    $stmt = $bdd->query('SELECT id, nom, adresse, visible FROM points_sortie');
                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $point_sortie) {
                      if (is_sortie_visible($point_sortie)) {
                        $nom = $point_sortie['nom'];
                        $url_query = "numero={$point_sortie['id']}&nom={$nom}&adresse={$point_sortie['adresse']}";
                        ?>
                        <li>
                          <a href="../ifaces/sortiesc.php?<?php echo $url_query; ?>"><?php echo $nom; ?></a>
                        </li>
                        <?php
                      }
                    }
                    ?>
                  </ul>
                </li>
                <?php
              }

              if (is_allowed_vente()) {
                ?>
                <li class="nav navbar-nav dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Points de vente<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li>
                      <?php
                      // On recupère tout le contenu de la table point de vente
                      $stmt = $bdd->query('SELECT id, nom, adresse, visible FROM points_vente');
                      foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $point_vente) {
                        if (is_vente_visible($point_vente)) {
                          $nom = $point_vente['nom'];
                          $url_query = "numero={$point_vente['id']}&nom={$nom}&adresse={$point_vente['adresse']}";
                          ?>
                        <li>
                          <a href="../ifaces/ventes.php?<?php echo $url_query; ?>"><?php echo $nom; ?></a>
                        </li>
                        <?php
                      }
                    }
                    ?>
                  </ul>
                </li>
                <?php
              }

              $now_date = date("d-m-Y");

              if (is_allowed_bilan()) {
                ?>
                <li><a href="../ifaces/bilanc.php?date1=<?php echo $now_date ?>&date2=<?php echo $now_date ?>&numero=0">Bilans</a></li>
                <?php
              }

              $can_gestion = is_allowed_gestion();
              $can_verif = is_allowed_verifications();
              $can_users = is_allowed_users();
              $can_parners = is_allowed_partners();
              $can_config = is_allowed_config();
              if ($can_gestion
                || $can_verif
                || $can_users
                || $can_parners
                || $can_config) {
                ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Gestion<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <?php
                    //Grille des prix et masse des bacs(gestion quotidienne)
                    if ($can_gestion) {
                      ?>
                      <li><a href="../ifaces/grilles_prix.php?typo=1">Grille des prix</a></li>
                      <li><a href="../ifaces/edition_types_contenants.php">Bacs et chariots</a></li>
                      <li><a href="../ifaces/edition_types_poubelles.php">Types de poubelles</a></li>
                      <li><a href="pesees_stats.php">Pésées pour statistiques</a></li>
                      <li><a href="etiquettes.php">Étiquettes boutique</a></li>
                      <li><a href="gains_recycleurs.php">Gains recycleurs</a></li>
                      <li class="divider"></li>
                      <?php
                    }

                    //gestion verif
                    if ($can_verif) {
                      ?>
                      <li><a href="../ifaces/verif_collecte.php?date1=<?php echo $now_date ?>&date2=<?php echo $now_date ?>&numero=1">Vérifier les collectes</a></li>
                      <li><a href="../ifaces/verif_sorties.php?date1=<?php echo $now_date ?>&date2=<?php echo $now_date ?>&numero=1">Vérifier les sorties hors-boutique</a></li>
                      <li><a href="../ifaces/verif_vente.php?date1=<?php echo $now_date ?>&date2=<?php echo $now_date ?>&numero=1">Vérifier les ventes</a></li>
                      <li class="divider"></li>
                      <?php
                    }

                    //utilisateurs
                    if ($can_users) {
                      ?>
                      <li><a href="../ifaces/utilisateurs.php">Utilisateurs</a></li>
                      <li class="divider"></li>
                      <?php
                    }

                    //recycleur et conventions de sortie
                    if ($can_parners) {
                      ?>
                      <li><a href="../ifaces/edition_filieres_sortie.php">Entreprises de recyclage</a></li>
                      <li><a href="../ifaces/edition_conventions_sortie.php">Conventions avec les partenaires</a></li>
                      <li class="divider"></li>
                      <?php
                    }

                    //configuration de oressource
                    if ($can_config) {
                      ?>
                      <li><a href="../ifaces/edition_types_sortie.php">Types de sorties hors-boutique</a></li>
                      <li><a href="../ifaces/types_collecte.php">Types de collectes</a></li>
                      <li class="divider"></li>
                      <li><a href="../ifaces/types_dechets.php">Types d'objets collectés</a></li>
                      <li><a href="../ifaces/types_dechets_evac.php">Types de déchets evacués</a></li>
                      <li class="divider"></li>
                      <li><a href="../ifaces/edition_points_collecte.php">Points de collecte</a></li>
                      <li><a href="../ifaces/edition_points_sorties.php">Points de sortie hors-boutique</a></li>
                      <li><a href="../ifaces/edition_points_vente.php">Points de vente</a></li>
                      <li class="divider"></li>
                      <li><a href="../ifaces/moyens_paiment.php">Moyens de paiment</a></li>
                      <li><a href="../ifaces/edition_localites.php">Localités</a></li>
                      <li><a href="../ifaces/edition_description.php">Configuration de Oressource</a></li>
                    <?php } ?>
                  </ul>
                </li>
              <?php } ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="../ifaces/edition_mdp_utilisateur.php">Mot de passe</a></li>
                  <li><a href="../moteur/destroy.php">Déconnexion</a></li>
                </ul>
              </li>
            <?php } ?>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <?php if (isset($_GET['err'])) { ?>
      <div class='alert alert-danger' style='width:80%;margin:auto;'><?php echo $_GET['err']; ?></div>
    <?php }
     if (isset($_GET['msg'])) { ?>
      <div class='alert alert-success alert-dismissable' style='width:80%;margin:auto;'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <?php echo $_GET['msg']; ?>
      </div>
    <?php }
