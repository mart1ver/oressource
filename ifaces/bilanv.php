<?php
session_start();

require_once '../moteur/dbconfig.php';
require_once('../core/session.php');
require_once('../core/requetes.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && is_allowed_bilan()) {

  $date1 = $_GET['date1'];
  $date2 = $_GET['date2'];
  ?>

  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker-bs3.css" />
    </head>

    <div class="container">
      <div class="row">
        <div class="col-md-11" >
          <h1>Bilan global</h1>
          <div class="col-md-4 col-md-offset-8" >
            <label for="reportrange">Choisissez la période à inspecter:</label><br>
            <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
              <i class="fa fa-calendar"></i>
              <span></span> <b class="caret"></b>
            </div>
          </div>

          <ul class="nav nav-tabs">
            <li><a href="bilanc.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&numero=0">Collectes</a></li>
            <li><a href="bilanhb.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&numero=0">Sorties hors-boutique</a></li>
            <li class="active"><a href="#">Ventes</a></li>
          </ul>
        </div>
      </div> <!-- row -->
    </div> <!-- container -->

    <hr/>
    <div class="row">
      <div class="col-md-8 col-md-offset-1" >
        <h2>Bilan des ventes de la structure</h2>
        <ul class="nav nav-tabs">
          <?php foreach (points_ventes($bdd) as $point_vente) { ?>
            <li class="<?= ($_GET['numero'] == $point_vente['id'] ? 'active' : '') ?>">
              <a href="bilanv.php?numero=<?= $point_vente['id'] ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>"><?= $point_vente['nom'] ?></a>
            </li>
          <?php } ?>
          <li class="<?= ($_GET['numero'] == 0 ? 'active' : '') ?>">
            <a href="bilanv.php?numero=0&date1=<?= $date1 ?>&date2=<?= $date2 ?>">Tous les points</a>
          </li>
        </ul>

        <div class="row">
          <h2><?= ($date1 == $date2) ? "Le $date1 :" : "Du $date1 au $date2 :" ?></h2>
          <?php
          //on convertit les deux dates en un format compatible avec la bdd
          $date1ft = date_create_from_format('d-m-Y', $date1);
          $time_debut = $date1ft->format('Y-m-d');
          $time_debut = $time_debut . " 00:00:00";
          $date2ft = date_create_from_format('d-m-Y', $date2);
          $time_fin = $date2ft->format('Y-m-d');
          $time_fin = $time_fin . " 23:59:59";

          if ($_GET['numero'] == 0) { // Pour tous les points de vente car numero == 0
            // On on verifie le chiffre total degagé
            $req = $bdd->prepare("SELECT SUM(vendus.prix * vendus.quantite) AS total
              FROM vendus
              WHERE DATE(vendus.timestamp)
              BETWEEN :du AND :au
              AND vendus.prix > 0 limit 1");
            $req->execute(array('du' => $time_debut, 'au' => $time_fin));
            $donnees = $req->fetch(PDO::FETCH_ASSOC);
            $mtotcolo = $donnees['total'];
            if ($mtotcolo == 0) {
              ?>
              <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
              <?php
            } else {
              // on determine le nombre de points de vente à cet instant
              $req = $bdd->query("SELECT COUNT(id) as nb_points_ventes FROM points_vente LIMIT 1");
              $nbPointV = (int) $req->fetch(PDO::FETCH_ASSOC)['nb_points_ventes'];
              // on determine le nombre d'objets vendus
              $req = $bdd->prepare("SELECT SUM(vendus.quantite) as nb_obj_vendu
                                              FROM vendus
                                              WHERE prix > 0
                                              AND DATE(vendus.timestamp)
                                              BETWEEN :du AND :au LIMIT 1");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin));
              $nbObjV = (int) $req->fetch(PDO::FETCH_ASSOC)['nb_obj_vendu'];

              // on determine le nombre de ventes
              $req = $bdd->prepare("SELECT COUNT(DISTINCT(ventes.id)) as nb_ventes
                          FROM ventes ,vendus
                          WHERE vendus.id_vente = ventes.id
                          AND DATE(vendus.timestamp)
                          BETWEEN :du AND :au
                          AND vendus.prix > 0 LIMIT 1");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin));
              $nbVentes = (int) $req->fetch(PDO::FETCH_ASSOC)['nb_ventes'];

              // on determine le nombre d'objets remboursés
              $req = $bdd->prepare("SELECT SUM(vendus.quantite) as nb_obj_remb
                                                FROM vendus
                                                WHERE remboursement > 0
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au LIMIT 1");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin));
              $nbObjR = (int) $req->fetch(PDO::FETCH_ASSOC)['nb_obj_remb'];

              // on determine le nombre de remboursement
              $req = $bdd->prepare("SELECT COUNT(DISTINCT(ventes.id)) as nb_remb
                                    FROM ventes ,vendus
                                    WHERE vendus.id_vente = ventes.id
                                    AND DATE(vendus.timestamp)
                                    BETWEEN :du AND :au
                                    AND vendus.remboursement > 0 LIMIT 1");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin));
              $nbR = $req->fetch(PDO::FETCH_ASSOC)['nb_remb'];

              // On recupère tout le contenu de la table point de vente
              $req = $bdd->prepare("SELECT SUM(vendus.remboursement) AS total
                                                FROM vendus
                                                WHERE DATE(vendus.timestamp)
                                                BETWEEN :du AND :au LIMIT 1");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin));
              $mtotcolo2 = $req->fetch()['total'];

              $req = $bdd->prepare("SELECT SUM(pesees_vendus.masse) as masse_caisse
                                    FROM pesees_vendus, vendus
                                    WHERE pesees_vendus.id_vendu = vendus.id
                                    AND DATE(vendus.timestamp)
                                    BETWEEN :du AND :au LIMIT 1");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin));
              $Mtpe = (float) $req->fetch(PDO::FETCH_ASSOC)['masse_caisse'];
              ?>
              <div class="row">
                <div class="col-md-6">
                  <table class='table table-hover'>
                    <tbody>
                      <tr>
                        <td>- Nombre de points de vente :</td>
                        <td><?= $nbPointV ?></td>
                      </tr>
                      <tr>
                        <td>- Chiffre total dégagé  :</td>
                        <td><?= $mtotcolo ?> €</td>
                      </tr>
                      <tr>
                        <td>- Nombre d'objets vendus :</td>
                        <td><?= $nbObjV ?></td>
                      </tr>
                      <tr>
                        <td>- Nombre de ventes :</td>
                        <td><?= $nbVentes ?></td>
                      </tr>
                      <tr>
                        <td>- Panier moyen :</td>
                        <td><?= $mtotcolo / $nbVentes ?> €</td>
                      </tr>
                      <tr>
                        <td>- Nombre d'objets remboursés :</td>
                        <td><?= $nbObjR ?>
                        </td>
                      </tr>
                      <tr>
                        <td>- Nombre de remboursemments :</td>
                        <td><?= $nbR ?></td>
                      </tr>
                      <tr>
                        <td>- Somme remboursée :</td>
                        <td><?= $mtotcolo2 ?> €</td>
                      </tr>
                      <tr>
                        <td>- Masse pesée en caisse :</td>
                        <td><?= $Mtpe ?> Kgs</td>
                      </tr>
                    </tbody>

                    <tfoot>
                      <tr>
                        <td align=center colspan=3>
                          <a href="../moteur/export_bilanv.php?numero=<?= $_GET['numero'] ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>">
                            <button type="button" class="btn btn-default btn-xs">Exporter les ventes de cette période (.csv)</button>
                          </a>
                        </td>
                      </tr>
                    </tfoot>
                  </table>

                  <h3>Récapitulatif par mode de paiement</h3>

                  <table class='table table-hover'>
                    <thead>
                      <tr>
                        <th>Moyen de Paiement</th>
                        <th>Nombre de Ventes</th>
                        <th>Chiffre Dégagé</th>
                        <th>Somme remboursée</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      // Tableau de recap du Chiffre d'Affaire par mode de paiement
                      // Utile pour vérifier le fond de caisse en fin de vente
                      // Equivalent de la touche 'Z' sur une caisse enregistreuse

                      $sql = 'SELECT
                                ventes.id_moyen_paiement AS id_moyen,
                                moyens_paiement.nom AS moyen,
                                COUNT(DISTINCT(ventes.id)) AS quantite_vendue,
                                SUM(vendus.prix*vendus.quantite) AS total,
                                SUM(vendus.remboursement) AS remboursement
                              FROM
                                ventes,
                                vendus,
                                moyens_paiement
                              WHERE
                                vendus.id_vente = ventes.id
                                 AND moyens_paiement.id = ventes.id_moyen_paiement
                                 AND DATE(vendus.timestamp) BETWEEN :du AND :au
                                GROUP BY ventes.id_moyen_paiement';

                      $req = $bdd->prepare($sql);
                      $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                      // Affichage du tableau
                      foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $ligne) {?>
                        <tr>
                          <td><?= $ligne['moyen'] ?></td>
                          <td><?= $ligne['quantite_vendue'] ?></td>
                          <td><?= $ligne['total'] ?> €</td>
                          <td><?= $ligne['remboursement'] ?> €</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <h4>Chiffre dégagé par type d'objet: </h4>
                  <div id="graphPV" style="height: 180px;"></div>
                </div>

                <div class="col-md-6 ">
                  <h3 style="text-align:center;">Chiffre de caisse : <?= $mtotcolo - $mtotcolo2 ?> €</h3>
                  <h4>=Récapitulatif par type d'objet=</h4>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>type d'objet</th>
                        <th>chiffre dégagé</th>
                        <th>quantité vendue</th>
                        <th>somme remboursée</th>
                        <th>quantité rembour.</th>
                        <th>masse pésee</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      // On recupère tout le contenu de la table affectations
                      $reponse2 = $bdd->prepare('SELECT type_dechets.id id,
                                                type_dechets.nom ,SUM(vendus.prix * vendus.quantite) total
                                                FROM type_dechets, vendus, ventes
                                                WHERE vendus.id_vente = ventes.id
                                                AND type_dechets.id = vendus.id_type_dechet
                                                AND DATE(vendus.timestamp) BETWEEN :du AND :au
                                                GROUP BY type_dechets.nom');
                      $reponse2->execute(array('du' => $time_debut, 'au' => $time_fin));

                      // on determine le nombre d'objets vendus
                      $stmt_vendu_quantite = $bdd->prepare('SELECT SUM(vendus.quantite)
                                                  FROM vendus WHERE prix > 0
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp)
                                                  BETWEEN :du AND :au limit 1');
                      $stmt_vendu_quantite->bindValue(':du', $time_debut, PDO::PARAM_STR);
                      $stmt_vendu_quantite->bindValue(':au', $time_fin, PDO::PARAM_STR);

                      $stmt_vendu_remb = $bdd->prepare('SELECT SUM(vendus.remboursement) AS total
                                                   FROM vendus
                                                   WHERE DATE(vendus.timestamp)
                                                   BETWEEN :du AND :au
                                                   AND vendus.id_type_dechet = :id limit 1');
                      $stmt_vendu_remb->bindValue(':du', $time_debut, PDO::PARAM_STR);
                      $stmt_vendu_remb->bindValue(':au', $time_fin, PDO::PARAM_STR);

                      // on determine le nombre d'objets remboursés pour ce type d'objet
                      $stmt_quantite_remb = $bdd->prepare("SELECT SUM(vendus.quantite) as somme_remb
                                                  FROM vendus WHERE remboursement > 0
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp)
                                                  BETWEEN :du AND :au limit 1");
                      $stmt_quantite_remb->bindValue(':du', $time_debut, PDO::PARAM_STR);
                      $stmt_quantite_remb->bindValue(':au', $time_debut, PDO::PARAM_STR);

                      // on determine la masse d'objets pesés vendu
                      $stmt_masse_vendus = $bdd->prepare("SELECT SUM(pesees_vendus.masse) as masse_vendu
                                                  FROM pesees_vendus , vendus
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp) BETWEEN :du AND :au limit 1");
                      $stmt_masse_vendus->bindValue(':du', $time_debut, PDO::PARAM_STR);
                      $stmt_masse_vendus->bindValue(':au', $time_debut, PDO::PARAM_STR);

                      while ($donnees2 = $reponse2->fetch(PDO::FETCH_ASSOC)) {
                        $stmt_vendu_quantite->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $stmt_vendu_quantite->execute();
                        $Nt = (int) $stmt_vendu_quantite->fetch(PDO::FETCH_ASSOC)['SUM(vendus.quantite)'];

                        // On determine la somme totale remboursée pour ce type d'objet
                        $stmt_vendu_remb->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $stmt_vendu_remb->execute();
                        $somme_remb = (double) $stmt_vendu_remb->fetch(PDO::FETCH_ASSOC)['total'];

                        $stmt_quantite_remb->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $stmt_quantite_remb->execute();
                        $quantite_remb = (int) $stmt_quantite_remb->fetch(PDO::FETCH_ASSOC)['somme_remb'];

                        $stmt_masse_vendus->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $stmt_masse_vendus->execute();
                        $masse_vendus = (double) $stmt_masse_vendus->fetch()['masse_vendu'];

                        $Mtpe = $masse_vendus;
                        ?>
                        <tr>
                          <th scope="row">
                            <a href="./jours.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&type=<?= $donnees2['id'] ?>"><?= $donnees2['nom'] ?></a>
                          </th>
                          <td><?= $donnees2['total'] ?> €</td>
                          <td><?= $Nt ?></td>
                          <td><?= $somme_remb ?> €</td>
                          <td><?= $quantite_remb ?></td>
                          <td><?= round($masse_vendus, 2) ?>Kgs.</td>
                        </tr>
                      <?php } ?>
                    </tbody>

                    <tfoot>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>

                  <h3>Récapitulatif des masses pesées à la caisse</h3>

                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>type d'objet</th>
                        <th>chiffre dégagé</th>
                        <th>masse pésee</th>
                        <th>nombre de pesées</th>
                        <th>nombre d'objets pesés</th>
                        <th>nombre d'objets vendus</th>
                        <th>masse sortie totale estimée</th>
                        <th>prix à la tonne estimé</th>
                        <th>certitude de l'estimation</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      // On recupère le nom du type d'objet et son C.A. lié
                      $reponse2 = $bdd->prepare('SELECT type_dechets.id id,
                                                type_dechets.nom,SUM(vendus.prix * vendus.quantite) total
                                                FROM type_dechets, vendus, ventes
                                                WHERE vendus.id_vente = ventes.id
                                                AND type_dechets.id = vendus.id_type_dechet
                                                AND DATE(vendus.timestamp) BETWEEN :du AND :au
                                                GROUP BY type_dechets.nom');
                      $reponse2->execute(['du' => $time_debut, 'au' => $time_fin]);

                      $stmt_vendu_pesee = $bdd->prepare("SELECT
                                              SUM(pesees_vendus.masse) as somme_pesee_ventes,
                                              COUNT(DISTINCT(pesees_vendus.id)) as nb_pesees_ventes,
                                              SUM(pesees_vendus.quantite) as quantite_pesee_vendu
                                              FROM pesees_vendus, vendus
                                              WHERE pesees_vendus.id_vendu = vendus.id
                                              AND vendus.id_type_dechet = :id
                                              AND DATE(vendus.timestamp) BETWEEN :du AND :au limit 1");

                      $stmt_vente_quantite = $bdd->prepare("SELECT
                                              SUM(vendus.quantite) as ventes_quantite
                                              FROM vendus, ventes
                                              WHERE prix > 0
                                              AND vendus.id_type_dechet = :id
                                              AND DATE(vendus.timestamp)
                                              BETWEEN :du AND :au
                                              AND ventes.id = vendus.id_vente limit 1");
                      
                      // on determine la masse moyenne d'un objet dans toute la base (pour le type d'objet en cours) = $Mm
                      $stmt_moy_masse_vente = $bdd->prepare("SELECT
                                                  AVG(pesees_vendus.masse) as moy_masse_vente
                                                  FROM pesees_vendus , vendus
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND pesees_vendus.masse > 0
                                                  AND vendus.id_type_dechet = :id limit 1");

                      // On affiche chaque entree une à une
                      foreach ($reponse2->fetchAll(PDO::FETCH_ASSOC) as $donnees2) {
                        $chiffre_degage = $donnees2['total'];
                        $id_type_dechet = $donnees2['id'];
                        // on determine la masse d'objets pesés
                        // on determine le nombre d'objets pesées
                        // on determine le nombre de pesés
                        $stmt_vendu_pesee->execute([
                            'du' => $time_debut,
                            'au' => $time_fin,
                            'id' => $id_type_dechet
                        ]);
                        $vendus_pesses = $stmt_vendu_pesee->fetch(PDO::FETCH_ASSOC);

                        $Mtpe = (double) $vendus_pesses['somme_pesee_ventes'];
                        $Ntpe = (int) $vendus_pesses['nb_pesees_ventes'];
                        $Notpe = (int) $vendus_pesses['quantite_pesee_vendu'];

                        $stmt_vente_quantite->execute(['du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id']]);
                        $donnees = $stmt_vente_quantite->fetch(PDO::FETCH_ASSOC);
                        // on determine le nombre d'objets vendus
                        $ov = (int) $donnees['ventes_quantite'];

                        $stmt_moy_masse_vente->execute(['id' => $donnees2['id']]);
                        $donnees = $stmt_moy_masse_vente->fetch();
                        $moy_masse_vente = (double) $donnees['moy_masse_vente'];

                        /*
                          echo "toto".$Mm."toto";
                          estimation de la masse totale vendue sur la periode pour tout les points de vente
                          masse moyenne d'un objet dans toute la base (pour le type d'objet en cours) = $Mm
                          nombre d'objets vendus (tout types confondus) = $Nt
                          nombre d'objets pesées sur la periode = $Np
                          masse totale d'objets peses sur cette periode = $Mtpe
                          nombre de pesées sur la periode pour le type d'objet = $Ntpe
                          nombre d'objets pesés sur la periode pour le type d'objet = $Notpe
                          nombre d'objets vendus sur la periode pour le type d'objet = $ov

                          if($ov == $Notpe) {
                          $mtee = $Mtpe;
                          $certitude = 100;
                          } else {
                          $mtee = (($Mm*$ov)-($Mm*$Np))+$Mtpe;
                          $certitude = 0;
                          }

                          $mtee = round((($Mm*$Nt)-($Mm*$Mp))+$Mtpe, 2);
                         */

                        if ($ov == $Notpe) {
                          $mtee = $Mtpe;
                          $certitude = 100;
                        } else {
                          $masse_vente_moyenne_totale = $moy_masse_vente * $ov;
                          $masse_pesees_vendu_esp = $moy_masse_vente * $Mtpe;
                          $prix_tonne_estime = ($masse_vente_moyenne_totale - $masse_pesees_vendu_esp) +
                            $Mtpe;
                          $certitude = round(($Notpe / $ov) * 100, 2);
                        }

                        //on traduit le pourcentage en valeur de vert 100% = tout vert
                        $Gvalue = round($certitude * 2.55, 0);
                        //on traduit le pourcentage en valeur de rouge 0% = tout rouge
                        $Rvalue = round(255 - $Gvalue, 0);
                        ?>
                        <tr>
                          <th scope="row">
                            <a href="./jours.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&type=<?= $donnees2['id'] ?>"><?= $donnees2['nom'] ?></a>
                          </th>
                          <td><?= $chiffre_degage ?> €</td>
                          <td><?= round($Mtpe, 2) ?> Kgs.</td>
                          <td><?= round($Ntpe, 2) ?></td>
                          <td><?= $Notpe ?></td>
                          <td><?= $ov ?></td>
                          <td><?= round($prix_tonne_estime, 2) ?> Kgs</td>
                          <td><?=
                            (($prix_tonne_estime > 0.0) ? round(($chiffre_degage /
                                $prix_tonne_estime) * 1000, 2) : '-' )
                            ?> €</td>
                          <td>
                            <span class='badge'
                                  id='Bcertitude'
                                  style='background-color: RGB(<?= $Rvalue ?>,<?= $Gvalue ?>,0);'
                                  ><?= $certitude ?> %</span>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>

                    <tfoot>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>

                  <h4>Masses pesées en caisse par type d'objet :</h4>

                  <div id="graphMV" style="height: 180px;"></div>
                </div>
              </div>
              <?php
            }
          } else {
            // si numero ==! 0
            // On on verifie le chiffre total degagé
            $req = $bdd->prepare('SELECT SUM(vendus.prix * vendus.quantite) AS total
                                  FROM vendus
                                  WHERE  DATE(vendus.timestamp)
                                  BETWEEN :du AND :au AND vendus.prix > 0 limit 1');
            $req->execute(['du' => $time_debut, 'au' => $time_fin]);
            $donnees = $req->fetch();
            $mtotcolo = $donnees['total'];

            if ($mtotcolo == 0) {
              ?>
              <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
              <?php
            } else {
              // On recupère tout le contenu de la table point de vente
              $req = $bdd->prepare('SELECT SUM(vendus.prix * vendus.quantite) AS total
                                    FROM vendus ,ventes
                                    WHERE DATE(ventes.timestamp) 
                                    BETWEEN :du AND :au
                                    AND ventes.id_point_vente  = :numero
                                    AND ventes.id = vendus.id_vente AND vendus.prix > 0 limit 1');
              $req->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);
              $donnees = $req->fetch();
              $mtotcolo = $donnees['total'];

              // on determine le nombre d'objets vendus
              $req = $bdd->prepare('SELECT SUM(vendus.quantite)
                             FROM vendus, ventes
                             WHERE vendus.prix > 0
                             AND DATE(vendus.timestamp)
                             BETWEEN :du AND :au
                             AND ventes.id_point_vente = :numero
                             AND ventes.id = vendus.id_vente limit 1');
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $sum_quantite_vendus = $req->fetch(PDO::FETCH_ASSOC)['SUM(vendus.quantite)'];

              // on determine le nombre de ventes
              $req = $bdd->prepare('SELECT COUNT(DISTINCT(ventes.id))
                                    FROM ventes ,vendus
                                    WHERE vendus.id_vente = ventes.id AND DATE(vendus.timestamp)
                                    BETWEEN :du AND :au
                                    AND vendus.prix > 0
                                    AND ventes.id_point_vente = :numero limit 1');
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $nb_vendus = $req->fetch(PDO::FETCH_ASSOC)['COUNT(DISTINCT(ventes.id))'];

              // on determine le nombre d'objets remboursés
              $req = $bdd->prepare('SELECT SUM(vendus.quantite) as nb_obj_remboursement
                        FROM vendus,ventes
                        WHERE vendus.remboursement > 0
                        AND DATE(vendus.timestamp)
                        BETWEEN :du AND :au
                        AND ventes.id_point_vente = :numero
                        AND ventes.id = vendus.id_vente limit 1');
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $nb_obj_reboursement = $req->fetch()['nb_obj_remboursement'];

              // on determine le nombre de remboursements
              $req = $bdd->prepare('SELECT COUNT(DISTINCT(ventes.id)) as nb_remboursement
                          FROM ventes ,vendus
                          WHERE vendus.id_vente = ventes.id
                          AND DATE(vendus.timestamp)
                          BETWEEN :du AND :au
                          AND vendus.remboursement > 0
                          AND ventes.id_point_vente = :numero limit 1');
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $nb_remboursement = $req->fetch()['nb_remboursement'];

              // On recupère tout le contenu de la table point de vente
              $req = $bdd->prepare("SELECT SUM(vendus.remboursement) AS total
                          FROM vendus,ventes
                          WHERE  DATE(vendus.timestamp)
                          BETWEEN :du AND :au
                          AND ventes.id_point_vente = :numero
                          AND ventes.id = vendus.id_vente limit 1");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $donnees = $req->fetch();
              $mtotcolo2 = $donnees['total'];

              $req = $bdd->prepare("SELECT SUM(pesees_vendus.masse)
              FROM pesees_vendus , vendus ,ventes
              WHERE pesees_vendus.id_vendu = vendus.id AND vendus.id_vente = ventes.id
              AND DATE(vendus.timestamp) BETWEEN :du AND :au AND ventes.id_point_vente  = :numero limit 1");
              $req->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);
              $donnees = $req->fetch();
              $Mtpe = $donnees['SUM(pesees_vendus.masse)'];
              ?>

              <div class="row">
                <div class="col-md-6">
                  <table class='table table-hover'>
                    <tbody>
                      <tr>
                        <td>-Chiffre total dégagé  :</td>
                        <td><?= $mtotcolo ?> €</td>
                      </tr>
                      <tr>
                        <td>-Nombre d'objets vendus : </td>
                        <td><?= $sum_quantite_vendus ?></td>
                      </tr>
                      <tr>
                        <td>-nombre de ventes : </td>
                        <td><?= $nb_vendus ?></td>
                      </tr>
                      <tr>
                        <td>-Panier moyen : </td>
                        <td><?= $mtotcolo / $nb_vendus ?> €</td>
                      </tr>
                      <tr>
                        <td>-nombre d'objets remboursés : </td>
                        <td><?= $nb_obj_reboursement ?></td>
                      </tr>
                      <tr>
                        <td>-nombre de remboursemments : </td>
                        <td><?= $nb_remboursement ?></td>
                      </tr>
                      <tr>
                        <td>-somme remboursée :</td>
                        <td><?= $mtotcolo2 ?> €</td>
                      </tr>
                      <tr>
                        <td>-masse pesée en caisse :</td>
                        <td><?= $Mtpe ?> Kgs.</td>
                      </tr>
                    </tbody>
                  </table>
                  <?php
                  // Tableau de recap du Chiffre d'Affaire par mode de paiement
                  // Utile pour vérifier le fond de caisse en fin de vente
                  // Equivalent de la touche 'Z' sur une caisse enregistreuse
                  $sql = 'SELECT
                            ventes.id_moyen_paiement AS id_moyen,
                            moyens_paiement.nom AS moyen,
                            COUNT(DISTINCT(ventes.id)) AS quantite_vendue,
                            SUM(vendus.prix * vendus.quantite) AS total,
                            SUM(vendus.remboursement) AS remboursement
                          FROM
                            ventes,
                            vendus,
                            moyens_paiement
                          WHERE
                            vendus.id_vente = ventes.id
                             AND moyens_paiement.id = ventes.id_moyen_paiement
                             AND DATE(vendus.timestamp) BETWEEN :du AND :au
                             AND ventes.id_point_vente  = :numero
                          GROUP BY ventes.id_moyen_paiement;';
                  $req = $bdd->prepare($sql);
                  $req->execute([
                      'du' => $time_debut,
                      'au' => $time_fin,
                      'numero' => $_GET['numero']
                  ]);
                  ?>

                  <h3>Récapitulatif par mode de paiement</h3>

                  <table class='table table-hover'>
                    <thead>
                      <tr>
                        <th>Moyen de Paiement</th>
                        <th>Nombre de Ventes</th>
                        <th>Chiffre Dégagé</th>
                        <th>Somme remboursée</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $ligne) { ?>
                        <tr>
                          <td><?= $ligne['moyen'] ?></td>
                          <td><?= $ligne['quantite_vendue'] ?></td>
                          <td><?= $ligne['total'] ?> €</td>
                          <td><?= $ligne['remboursement'] ?> €</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>

                  <h4>Chiffre dégagé par type d'objet :</h4>

                  <div id="graphPV" style="height: 180px;"></div>
                </div>

                <div class="col-md-5 col-md-offset-1">
                  <h2>chiffre de caisse : <?= ($mtotcolo - $mtotcolo2) ?> €</h2>
                  <h3>Récapitulatif par type d'objet</h3>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>type d'objet</th>
                        <th>chiffre dégagé</th>
                        <th>quantité vendue</th>
                        <th>somme remboursée</th>
                        <th>quantité rembour.</th>
                        <th>masse pésee</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      // type d'objet.. et chiffre
                      $reponse2 = $bdd->prepare('SELECT type_dechets.id id,
                                                  type_dechets.nom, SUM(vendus.prix * vendus.quantite) total
                                                  FROM type_dechets , vendus, ventes
                                                  WHERE vendus.id_vente = ventes.id  AND ventes.id_point_vente  = :numero
                                                  AND type_dechets.id = vendus.id_type_dechet
                                                  AND DATE(vendus.timestamp) BETWEEN :du AND :au
                                                  GROUP BY type_dechets.nom');
                      $reponse2->bindValue(':du', $time_debut, PDO::PARAM_STR);
                      $reponse2->bindValue(':au', $time_fin, PDO::PARAM_STR);
                      $reponse2->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);
                      $reponse2->execute();

                      $req_ventes = $bdd->prepare("SELECT SUM(vendus.quantite) as total
                                                   FROM vendus, ventes
                                                   WHERE prix > 0
                                                   AND vendus.id_type_dechet = :id
                                                   AND DATE(vendus.timestamp)
                                                   BETWEEN :du AND :au
                                                   AND ventes.id = vendus.id_vente
                                                   AND ventes.id_point_vente = :numero limit 1");
                      $req_ventes->bindValue(":du", $time_debut, PDO::PARAM_STR);
                      $req_ventes->bindValue(":au", $time_fin, PDO::PARAM_STR);
                      $req_ventes->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                      $req_somme_remb = $bdd->prepare("SELECT SUM(vendus.remboursement) AS total
                                                    FROM vendus, ventes
                                                    WHERE DATE(vendus.timestamp)
                                                    BETWEEN :du AND :au
                                                    AND vendus.id_type_dechet = :id
                                                    AND ventes.id = vendus.id_vente
                                                    AND ventes.id_point_vente  = :numero limit 1");
                      $req_somme_remb->bindValue(":du", $time_debut, PDO::PARAM_STR);
                      $req_somme_remb->bindValue(":au", $time_fin, PDO::PARAM_STR);
                      $req_somme_remb->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                      $req_remb = $bdd->prepare("SELECT SUM(vendus.quantite) as total
                                                FROM vendus, ventes
                                                WHERE remboursement > 0
                                                AND vendus.id_type_dechet = :id
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au AND ventes.id_point_vente = :numero
                                                AND ventes.id = vendus.id_vente limit 1");
                      $req_remb->bindValue(":du", $time_debut, PDO::PARAM_STR);
                      $req_remb->bindValue(":au", $time_fin, PDO::PARAM_STR);
                      $req_remb->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                      // on determine la masse d'objets pesés
                      $reqMtpe = $bdd->prepare('SELECT SUM(pesees_vendus.masse)
                                                  FROM pesees_vendus , vendus ,ventes
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp)
                                                  BETWEEN :du AND :au
                                                  AND ventes.id_point_vente = :numero
                                                  AND ventes.id = vendus.id_vente limit 1');
                      $reqMtpe->bindValue(":du", $time_debut, PDO::PARAM_STR);
                      $reqMtpe->bindValue(":au", $time_fin, PDO::PARAM_STR);
                      $reqMtpe->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                      foreach ($reponse2->fetchAll(PDO::FETCH_ASSOC) as $donnees2) {
                        // on determine le nombre d'objets vendus
                        $req_ventes->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $req_ventes->execute();
                        $Nt = (int) $req_ventes->fetch(PDO::FETCH_ASSOC)['total'];

                        // on determine la somme des remboursements
                        $req_somme_remb->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $req_somme_remb->execute();
                        $somme_remb = (double) $req_somme_remb->fetch(PDO::FETCH_ASSOC)['total'];

                        // on determine le nombre d'objets remboursés
                        $req_remb->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $req_remb->execute();
                        $nb_remb = (int) $req_remb->fetch(PDO::FETCH_ASSOC)['total'];

                        $reqMtpe->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                        $reqMtpe->execute();
                        $Mtpe = (double) $reqMtpe->fetch(PDO::FETCH_ASSOC)['SUM(pesees_vendus.masse)'];
                        ?>
                        <tr>
                          <th scope="row">
                            <a href="./jours.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&type=<?= $donnees2['id'] ?>"><?= $donnees2['nom'] ?></a>
                          </th>
                          <td><?= $donnees2['total'] ?> €</td>
                          <td><?= $Nt ?></td>
                          <td><?= $somme_remb ?> €</td>
                          <td><?= $nb_remb ?></td>
                          <td><?= round($Mtpe, 2) ?> Kgs.</td>
                        </tr>
                      <?php } ?>

                    <h3>Récapitulatif des masses pesées à la caisse</h3>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>type d'objet</th>
                          <th>chiffre dégagé</th>
                          <th>masse pésee</th>
                          <th>nombre de pesées</th>
                          <th>nombre d'objets pesés</th>
                          <th>nombre d'objets vendus</th>
                          <th>masse sortie totale estimée</th>
                          <th>prix à la tonne estimé</th>
                          <th>certitude de l'estimation</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        // On recupère le nom du type d'objet et son C.A. lié
                        $reponse2 = $bdd->prepare('SELECT type_dechets.id id,
                                                    type_dechets.nom, SUM(vendus.prix * vendus.quantite) as total
                                                    FROM type_dechets, vendus, ventes
                                                    WHERE vendus.id_vente = ventes.id
                                                    AND type_dechets.id = vendus.id_type_dechet
                                                    AND DATE(vendus.timestamp)
                                                    BETWEEN :du AND :au
                                                    AND ventes.id_point_vente = :numero');
                        $reponse2->bindValue(':du', $time_debut, PDO::PARAM_STR);
                        $reponse2->bindValue(':au', $time_fin, PDO::PARAM_STR);
                        $reponse2->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);
                        $reponse2->execute();

                        // on determine le nombre de pesés
                        $reqNtpe = $bdd->prepare("SELECT COUNT(DISTINCT(pesees_vendus.id))
                                                    FROM pesees_vendus , vendus ,ventes
                                                    WHERE pesees_vendus.id_vendu = vendus.id
                                                    AND vendus.id_type_dechet = :id
                                                    AND DATE(vendus.timestamp)
                                                    BETWEEN :du AND :au
                                                    AND vendus.id_vente = ventes.id
                                                    AND ventes.id_point_vente = :numero limit 1");
                        $reqNtpe->bindValue(":du", $time_debut, PDO::PARAM_STR);
                        $reqNtpe->bindValue(":au", $time_fin, PDO::PARAM_STR);
                        $reqNtpe->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                        $reqOv = $bdd->prepare("SELECT SUM(vendus.quantite)
                                                FROM vendus,ventes WHERE prix > 0
                                                AND vendus.id_type_dechet = :id
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au
                                                AND ventes.id = vendus.id_vente
                                                AND ventes.id_point_vente = :numero limit 1");
                        $reqOv->bindValue(":du", $time_debut, PDO::PARAM_STR);
                        $reqOv->bindValue(":au", $time_fin, PDO::PARAM_STR);
                        $reqOv->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                        $reqNotpe = $bdd->prepare("SELECT SUM(pesees_vendus.quantite)
                                                    FROM pesees_vendus, vendus ,ventes
                                                    WHERE pesees_vendus.id_vendu = vendus.id
                                                    AND vendus.id_type_dechet = :id
                                                    AND DATE(vendus.timestamp)
                                                    BETWEEN :du AND :au
                                                    AND vendus.id_vente = ventes.id
                                                    AND ventes.id_point_vente = :numero limit 1");

                        $reqNotpe->bindValue(":du", $time_debut, PDO::PARAM_STR);
                        $reqNotpe->bindValue(":au", $time_fin, PDO::PARAM_STR);
                        $reqNotpe->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                        // On determine la masse moyenne d'un objet dans toute la base (pour le type d'objet en cours) = $Mm
                        $reqMm = $bdd->prepare("SELECT AVG(pesees_vendus.masse)
                                              FROM pesees_vendus, vendus
                                              WHERE pesees_vendus.id_vendu = vendus.id
                                              AND pesees_vendus.masse > 0
                                              AND vendus.id_type_dechet = :id limit 1");

                        $reqMtpe = $bdd->prepare("SELECT SUM(pesees_vendus.masse)
                                                  FROM pesees_vendus , vendus ,ventes
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp)
                                                  BETWEEN :du AND :au
                                                  AND vendus.id_vente = ventes.id
                                                  AND ventes.id_point_vente  = :numero limit 1");
                        $reqMtpe->bindValue(":du", $time_debut, PDO::PARAM_STR);
                        $reqMtpe->bindValue(":au", $time_fin, PDO::PARAM_STR);
                        $reqMtpe->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);

                        foreach ($reponse2->fetchAll(PDO::FETCH_ASSOC) as $donnees2) {
                          $reqNtpe->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                          $reqNtpe->execute();
                          $Ntpe = (int) $req->fetch(PDO::FETCH_ASSOC)['COUNT(DISTINCT(pesees_vendus.id))'];
                          // on determine le nombre d'objets vendus

                          $reqOv->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                          $reqOv->execute();
                          $ov = (int) $reqOv->fetch()['SUM(vendus.quantite)'];

                          // on determine le nombre d'objets pesés
                          $reqNotpe->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                          $reqNotpe->execute();
                          $Notpe = (int) $reqNotpe->fetch(PDO::FETCH_ASSOC)['SUM(pesees_vendus.quantite)'];

                          $reqMm->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                          $reqMm->execute();
                          $reqMm->execute(array('id' => $donnees2['id']));
                          $Mm = (double) $reqMm->fetch(PDO::FETCH_ASSOC)['AVG(pesees_vendus.masse)'];

                          // on determine la masse d'objets pesés
                          $reqMtpe->bindValue(':id', $donnees2['id'], PDO::PARAM_INT);
                          $reqMtpe->execute();
                          $Mtpe = (double) $reqMtpe->fetch(PDO::FETCH_ASSOC)['SUM(pesees_vendus.masse)'];

                          $chiffre_degage = $donnees2['total'];

                          /*
                            Estimation de la masse totale vendue sur la periode pour tout les points de vente

                            masse moyenne d'un objet dans toute la base (pour le type d'objet en cours) = $Mm
                            nombre d'objets vendus (tout types confondus) = $Nt
                            nombre d'objets pesées sur la periode = $Np // Pas defini!
                            masse totale d'objets peses sur cette periode = $Mtpe
                            nombre de pesées sur la periode pour le type d'objet = $Ntpe
                            nombre d'objets pesés sur la periode pour le type d'objet = $Notpe
                            nombre d'objets vendus sur la periode pour le type d'objet = $ov

                            if($ov == $Notpe) {
                            $mtee = $Mtpe;
                            $certitude = 100;
                            } else  {
                            $mtee = (($Mm*$ov)-($Mm*$Np))+$Mtpe;
                            $certitude = 0;
                            }
                            $mtee = round((($Mm*$Nt)-($Mm*$Mp))+$Mtpe, 2);

                           * FIXME: $Np est pas defini.
                            if ($ov == $Notpe) {
                            $mtee = $Mtpe;
                            $certitude = 100;
                            } else {
                            $mtee = (($Mm * $ov) - ($Mm * $Np)) + $Mtpe;
                            $certitude = round(($Notpe / $ov) * 100, 2);
                            }
                           */
                          ?>
                          <tr>
                            <th scope="row">
                              <a href=" jours.php?date1=<?= $_GET['date1'] ?>&date2=<?= $_GET['date2'] ?>&type=<?= $donnees2['id'] ?>"><?= $donnees2['nom'] ?></a>
                            </th>
                            <td><?= $chiffre_degage ?> €</td>
                            <td><?= round($Mtpe, 2) ?> Kgs.</td>
                            <td><?= round($Ntpe, 2) ?></td>
                            <td><?= $Notpe ?></td>
                            <td><?= $ov ?></td>
                            <td><?php /* echo $Mm;  echo round($mtee, 2); */ echo 'Pas Implemente'; ?> Kgs.</td>
                            <td><?php /* echo round(($chiffre_degage / $mtee) * 1000, 2); */ echo 'Pas Implemente'; ?> €</td>
                            <td><?php /* echo $certitude; */ echo 'pas implemente!' ?> %</td>
                          </tr>
                        <?php } ?>
                      </tbody>

                      <tfoot>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>
                    </tbody>
                  </table>

                  <h4>Masses pesées en caisse par type d'objet: </h4>

                  <div id="graphMV" style="height: 180px;"></div>
                  <br>
                  <br>
                </div>
              </div>
              <?php
            }
          }
          ?>
        </div>
      </div>
    </div>

    <?php
    if ($_GET['numero'] > 0) {
      $stmt = $bdd->prepare('SELECT type_dechets.couleur, type_dechets.nom,
                                    SUM(pesees_vendus.masse) as somme
                    FROM type_dechets, vendus, pesees_vendus, ventes
                    WHERE type_dechets.id = vendus.id_type_dechet
                    AND vendus.id = pesees_vendus.id_vendu
                    AND DATE(vendus.timestamp)
                    BETWEEN :du AND :au AND vendus.prix > 0
                    AND vendus.id_vente = ventes.id
                    AND ventes.id_point_vente = :numero
                    GROUP BY nom');
      $stmt->bindValue(':du', $time_debut, PDO::PARAM_STR);
      $stmt->bindValue(':au', $time_fin, PDO::PARAM_STR);
      $stmt->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);
      $graphMv = data_graphs($stmt);

      $stmt = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.prix) somme
                          FROM type_dechets, vendus,ventes
                          WHERE type_dechets.id = vendus.id_type_dechet
                          AND vendus.id_vente = ventes.id
                          AND DATE(vendus.timestamp)
                          BETWEEN :du AND :au
                          AND vendus.prix > 0
                          AND ventes.id_point_vente = :numero
                          GROUP BY nom');
      $stmt->bindValue(':du', $time_debut, PDO::PARAM_STR);
      $stmt->bindValue(':au', $time_fin, PDO::PARAM_STR);
      $stmt->bindValue(':numero', $_GET['numero'], PDO::PARAM_INT);
      $graphPV = data_graphs($stmt);
    } else {
      $stmt = $bdd->prepare('SELECT type_dechets.couleur, type_dechets.nom, SUM(pesees_vendus.masse) as somme
                    FROM type_dechets, vendus, pesees_vendus
                    WHERE type_dechets.id = vendus.id_type_dechet
                    AND vendus.id = pesees_vendus.id_vendu
                    AND DATE(vendus.timestamp)
                    BETWEEN :du AND :au
                    AND vendus.prix > 0
                    GROUP BY nom');
      $stmt->bindValue(':du', $time_debut, PDO::PARAM_STR);
      $stmt->bindValue(':au', $time_fin, PDO::PARAM_STR);
      $graphMv = data_graphs($stmt);
      
      $stmt = $bdd->prepare('SELECT type_dechets.couleur, type_dechets.nom, sum(vendus.prix) as somme
                            FROM type_dechets,vendus
                            WHERE type_dechets.id = vendus.id_type_dechet
                            AND DATE(vendus.timestamp)
                            BETWEEN :du AND :au
                            AND vendus.prix > 0
                            GROUP BY nom');
      $stmt->bindValue(':du', $time_debut, PDO::PARAM_STR);
      $stmt->bindValue(':au', $time_fin, PDO::PARAM_STR);
      $graphPV = data_graphs($stmt);
    }
    ?>
    <script type="text/javascript" src="../js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script src="../js/raphael.js"></script>
    <script src="../js/morris/morris.js"></script>
    <script type="text/javascript" src="../js/moment.js"></script>
    <script type="text/javascript" src="../js/daterangepicker.js"></script>
    <script type="text/javascript">
      'use strict';
      function process_get(param) {
        let val = {};
        const query = new URLSearchParams(window.location.search.slice(1)).entries();
        for (const pair of query) {
          val[pair[0]] = pair[1];
        }
        return val;
      }

      function cb(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
        $('#reportrange span').html(`${start.format('DD, MMMM, YYYY')} - ${end.format('DD, MMMM, YYYY')}`);
      }

      const get = process_get();
      const startDate = moment(get.date1, 'DD-MM-YYYY');
      const endDate = moment(get.date1, 'DD-MM-YYYY');

      const now = moment();
      const optionSet1 = {
        startDate: startDate.format('DD/MM/YYYY'),
        endDate: endDate.format('DD/MM/YYYY'),
        minDate: '01/01/2010',
        maxDate: '12/31/2020',
        dateLimit: {days: 800},
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
          "Aujoud'hui": [now, now],
          'hier': [now.subtract(1, 'days'), now.subtract(1, 'days')],
          '7 derniers jours': [now.subtract(6, 'days'), now],
          '30 derniers jours': [now.subtract(29, 'days'), now],
          'Ce mois': [
            now.startOf('month'),
            now.endOf('month')
          ],
          'Le mois deriner': [
            now.subtract(1, 'month').startOf('month'),
            now.subtract(1, 'month').endOf('month')
          ]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'DD/MM/YYYY',
        separator: ' to ',
        locale: {
          applyLabel: 'Appliquer',
          cancelLabel: 'Anuler',
          fromLabel: 'Du',
          toLabel: 'Au',
          customRangeLabel: 'Période libre',
          daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
          monthNames: ['Janvier', 'Fevrier', 'Mars'
                    , 'Avril', 'Mai', 'Juin'
                    , 'Juillet', 'Aout', 'Septembre'
                    , 'Octobre', 'Novembre', 'Decembre'],
          firstDay: 1
        }
      };

      $(document).ready(() => {
        {
          const picker_element = $('#reportrange');
          picker_element.daterangepicker(optionSet1, cb);
          $('#reportrange span').html(`${startDate.format('DD/MM/YYYY')} - ${endDate.format('DD/MM/YYYY')}`);

          picker_element.on('show.daterangepicker', () => {
            console.log("show event fired");
          });

          picker_element.on('hide.daterangepicker', () => {
            console.log("hide event fired");
          });

          picker_element.on('apply.daterangepicker',
                  (ev, picker) => {
            console.log(`apply event fired, start/end dates are ${picker.startDate.format('DD MM, YYYY')} to ${picker.endDate.format('DD MM, YYYY')}`);
            const start = picker.startDate.format('DD-MM-YYYY');
            const end = picker.endDate.format('DD-MM-YYYY');
            window.location.href = `./bilanv.php?date1=${start}&date2=${end}&numero=${get.numero}`;
          });

          picker_element.on('cancel.daterangepicker', (ev, picker) => {
            console.log("cancel event fired");
          });

          $('#options1').click(() => {
            picker_element.data('daterangepicker').setOptions(optionSet1, cb);
          });

          $('#options2').click(() => {
            picker_element.data('daterangepicker').setOptions(optionSet2, cb);
          });

          $('#destroy').click(() => {
            picker_element.data('daterangepicker').remove();
          });
        }

        try {
          const dataMv = <?= json_encode($graphMv, JSON_NUMERIC_CHECK) ?>;
          Morris.Donut({
            element: 'graphMV',
            data: dataMv.data,
            backgroundColor: '#ccc',
            labelColor: '#060',
            colors: dataMv.colors,
            formatter: (x) => {
              return `${x} Kgs.`;
            }
          });
        } catch (e) {
          console.error(e);
        }

        try {
          const dataPv = <?= json_encode($graphPV, JSON_NUMERIC_CHECK) ?>;
          Morris.Donut({
            element: 'graphPV',
            data: dataPv.data,
            backgroundColor: '#ccc',
            labelColor: '#060',
            colors: dataPv.colors,
            formatter: (x) => {
              return `${x}  €.`;
            }
          });
        } catch (e) {
          console.error(e);
        }
      });
    </script>

    <?php
    include "pied_bilan.php";
  } else {
    header('Location: ../moteur/destroy.php');
  }
