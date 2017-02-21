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
            <li class="active"><a>Ventes</a></li>
          </ul>
        </div>
      </div> <!-- row -->
    </div> <!-- container -->
    <hr/>
    <div class="row">
      <div class="col-md-8 col-md-offset-1" >
        <h2>Bilan des ventes de la structure</h2>
        <ul class="nav nav-tabs">
          <?php
          //on affiche un onglet par point de vente
          // On recupère tout le contenu des visibles de la table points_vente
          $reponse = $bdd->query('SELECT id, visible, nom FROM points_vente');

          // On affiche chaque entree une à une
          while ($donnees = $reponse->fetch()) {
            // Si le Point de Vente n'est pas visible, on passe directement au prochain
            if ($donnees['visible'] != "oui")
              continue;
            ?>
            <li<?php
            if ($_GET['numero'] == $donnees['id']) {
              echo ' class="active"';
            }
            ?>><a href="<?php echo "bilanv.php?numero=" . $donnees['id'] . "&date1=" . $date1 . "&date2=" . $date2 ?>"><?= $donnees['nom'] ?></a></li>
              <?php
            }
            $reponse->closeCursor(); // Termine le traitement de la requête
            // sortis de la boucle on affiche un onglet special "touts les points"
            ?>

          <li<?php
          if ($_GET['numero'] == 0) {
            echo ' class="active"';
          }
          ?>><a href="<?php echo "bilanv.php?numero=0&date1=" . $date1 . "&date2=" . $date2 ?>">Tous les points</a>
          </li>
        </ul>

        <div class="row">
          <h2>
            <?php
            // on affiche la période visée
            if ($date1 == $date2)
              echo 'Le ' . $date1 . " :";
            else
              echo 'Du ' . $date1 . " au " . $date2 . " :";
            ?>
          </h2>
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
              AND vendus.prix > 0");
            $req->execute(array('du' => $time_debut, 'au' => $time_fin));
            $donnees = $req->fetch();
            $req->closeCursor(); // Libère la connexion au serveur
            $mtotcolo = $donnees['total'];
            if ($mtotcolo == 0) {
              ?>
              <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
              <?php
            } else {
              ?>
              <div class="row">
                <div class="col-md-6">
                  <table class='table table-hover'>
                    <tbody>
                      <tr>
                        <td>- Nombre de points de vente :</td>
                        <?php
                        // on determine le nombre de points de vente à cet instant
                        $req = $bdd->query("SELECT COUNT(id) FROM points_vente");
                        $donnees = $req->fetch();
                        $req->closeCursor();
                        $nbPointV = $donnees[0];
                        ?>
                        <td><?= $nbPointV ?></td>
                      </tr>
                      <tr>
                        <td>- Chiffre total dégagé  :</td>
                        <td><?= $mtotcolo ?> €</td>
                      </tr>
                      <tr>
                        <td>- Nombre d'objets vendus :</td>
                        <?php
                        // on determine le nombre d'objets vendus
                        $req = $bdd->prepare("SELECT SUM(vendus.quantite) FROM vendus WHERE prix > 0 AND DATE(vendus.timestamp) BETWEEN :du AND :au ");
                        $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                        $donnees = $req->fetch();
                        $req->closeCursor();
                        $nbObjV = $donnees[0];
                        ?>
                        <td><?= $nbObjV ?></td>
                      </tr>
                      <tr>
                        <td>- Nombre de ventes :</td>
                        <?php
                        // on determine le nombre de ventes
                        $req = $bdd->prepare("SELECT COUNT(DISTINCT(ventes.id))
                          FROM ventes ,vendus
                          WHERE vendus.id_vente = ventes.id
                          AND DATE(vendus.timestamp)
                          BETWEEN :du AND :au
                          AND vendus.prix > 0");
                        $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                        $donnees = $req->fetch();
                        $req->closeCursor();
                        $nbVentes = $donnees[0];
                        ?>
                        <td><?= $nbVentes ?></td>
                      </tr>
                      <tr>
                        <td>- Panier moyen :</td>
                        <td><?= $mtotcolo / $nbVentes ?> €</td>
                      </tr>
                      <tr>
                        <td>- Nombre d'objets remboursés :</td>
                        <td>
                          <?php
                          // on determine le nombre d'objets remboursés
                          $req = $bdd->prepare("SELECT SUM(vendus.quantite)
                                                FROM vendus
                                                WHERE remboursement > 0
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au");
                          $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                          $donnees = $req->fetch();
                          $req->closeCursor();
                          $nbObjR = $donnees[0];
                          if ($nbObjR == 0)
                            echo '-';
                          else
                            echo $nbObjR;
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>- Nombre de remboursemments :</td>
                        <td>
                          <?php
                          // on determine le nombre de remboursement
                          $req = $bdd->prepare("SELECT COUNT(DISTINCT(ventes.id))
                                                FROM ventes ,vendus
                                                WHERE vendus.id_vente = ventes.id
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au
                                                AND vendus.remboursement > 0 ");
                          $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                          $donnees = $req->fetch();
                          $req->closeCursor();
                          $nbR = $donnees[0];
                          if ($nbR == 0)
                            echo '-';
                          else
                            echo $nbR;
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>- Somme remboursée :</td>
                        <td>
                          <?php
                          // On recupère tout le contenu de la table point de vente
                          $req = $bdd->prepare("SELECT SUM(vendus.remboursement) AS total
                                                FROM vendus
                                                WHERE DATE(vendus.timestamp)
                                                BETWEEN :du AND :au  ");
                          $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                          $totR = $req->fetch();
                          $req->closeCursor();
                          $mtotcolo2 = $totR['total'];
                          if ($mtotcolo2 == 0)
                            echo '-';
                          else
                            echo $mtotcolo2 . '€';
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>- Masse pesée en caisse :</td>
                        <td>
                          <?php
                          $req = $bdd->prepare("SELECT SUM(pesees_vendus.masse)
                                                FROM pesees_vendus, vendus
                                                WHERE pesees_vendus.id_vendu = vendus.id
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au ");
                          $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                          $donnees = $req->fetch();
                          $req->closeCursor();
                          $Mtpe = $donnees[0];
                          if (intval($Mtpe) == 0)
                            echo '-';
                          else
                            echo $Mtpe . 'Kgs';
                          ?>
                        </td>
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

                      $sql = file_get_contents('../mysql/recap_CA_par_mode_paiement_tout_les points.sql');
                      $req = $bdd->prepare($sql);
                      $ok = $req->execute(array('du' => $time_debut, 'au' => $time_fin));
                      // Affichage du tableau
                      while ($ligne = $req->fetch()) {
                        ?>
                        <tr>
                          <td><?php echo($ligne['moyen']); ?></td>
                          <td><?php echo($ligne['quantite_vendue']); ?></td>
                          <td><?php echo($ligne['total']); ?> €</td>
                          <td><?php echo($ligne['remboursement']); ?> €</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <h4>Chiffre dégagé par type d'objet: </h4>
                  <div id="graphPV" style="height: 180px;"></div>
                </div>
                <div class="col-md-6 ">
                  <h3 style="text-align:center;">
                    chiffre de caisse : <?php echo $mtotcolo - $mtotcolo2 . " €"; ?>
                  </h3>
                  <br>
                  <h3>=Récapitulatif par type d'objet=</h3>
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
                      // On affiche chaque entree une à une
                      while ($donnees2 = $reponse2->fetch()) {
                        ?>
                        <tr>
                          <th scope="row"> <a href=" jours.php?date1=<?php echo $_GET['date1'] ?>&date2=<?php echo $_GET['date2'] ?>&type=<?php echo $donnees2['id'] ?>" > <?php echo $donnees2['nom'] ?> </a></th>
                          <td><?php echo $donnees2['total'] . " €" ?></td>
                          <td>
                            <?php
                            // on determine le nombre d'objets vendus
                            $req = $bdd->prepare("SELECT SUM(vendus.quantite)
                                                  FROM vendus WHERE prix > 0
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp)
                                                  BETWEEN :du AND :au ");
                            $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id']));
                            $donnees = $req->fetch();
                            echo $donnees['SUM(vendus.quantite)'];
                            $Nt = $donnees['SUM(vendus.quantite)'];
                            $req->closeCursor(); // Termine le traitement de la requête
                            ?>
                          </td>
                          <td>
                            <?php
                            // On determinela somme totale remboursée pour ce type d'objet
                            $req3 = $bdd->prepare("SELECT SUM(vendus.remboursement) AS total
                                                   FROM vendus
                                                   WHERE DATE(vendus.timestamp)
                                                   BETWEEN :du AND :au AND vendus.id_type_dechet = :id");
                            $req3->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id']));
                            $donnees3 = $req3->fetch();
                            if ($donnees3['total'] == 0) {
                              echo "-" . "<br>";
                            } else {
                              echo $donnees3['total'] . " €";
                            }
                            $req3->closeCursor(); // Termine le traitement de la requête
                            ?>
                          </td>

                          <td >
                            <?php
                            // on determine le nombre d'objets remboursés pour ce type d'objet
                            $req = $bdd->prepare("SELECT SUM(vendus.quantite) FROM vendus WHERE remboursement > 0
                                                  AND vendus.id_type_dechet = :id AND DATE(vendus.timestamp) BETWEEN :du AND :au ");
                            $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id']));
                            $donnees = $req->fetch();
                            if ($donnees3['total'] == 0) {
                              echo "-";
                            } else {
                              echo intval($donnees['SUM(vendus.quantite)']);
                            }
                            $req->closeCursor(); // Termine le traitement de la requête
                            ?>
                          </td>
                          <td> <?php
                            // on determine la masse d'objets pesés
                            $req = $bdd->prepare("SELECT SUM(pesees_vendus.masse)
                                                  FROM pesees_vendus , vendus
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp) BETWEEN :du AND :au ");
                            $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id']));
                            $donnees = $req->fetch();
                            echo round($donnees['SUM(pesees_vendus.masse)'], 2) . " Kgs.";
                            $Mtpe = $donnees['SUM(pesees_vendus.masse)'];
                            $req->closeCursor(); // Termine le traitement de la requête
                            ?></td>
                        </tr>
                        <?php
                      }
                      $reponse2->closeCursor(); // Termine le traitement de la requête
                      ?>

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
                  <h3>
                    Récapitulatif des masses pesées à la caisse
                  </h3>
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
                                                type_dechets.nom ,SUM(vendus.prix*vendus.quantite) total=
                                                FROM type_dechets , vendus, ventes
                                                WHERE vendus.id_vente = ventes.id
                                                AND type_dechets.id = vendus.id_type_dechet
                                                AND DATE(vendus.timestamp) BETWEEN :du AND :au
                                                GROUP BY type_dechets.nom
                                             ');
                      $reponse2->execute(array('du' => $time_debut, 'au' => $time_fin));
                      // On affiche chaque entree une à une
                      while ($donnees2 = $reponse2->fetch()) {
                        $cd = $donnees2['total'];
                        $id_type_dechet = $donnees2['id'];
                        // on determine la masse d'objets pesés
                        // on determine le nombre d'objets pesées
                        // on determine le nombre de pesés
                        $req = $bdd->prepare("SELECT
                                              SUM(pesees_vendus.masse) as somme_pesee_ventes,
                                              COUNT(DISTINCT(pesees_vendus.id)) as nb_pesees_ventes,
                                              SUM(pesees_vendus.quantite) as quantite_pesee_vendu
                                              FROM pesees_vendus, vendus
                                              WHERE pesees_vendus.id_vendu = vendus.id
                                              AND vendus.id_type_dechet = :id
                                              AND DATE(vendus.timestamp) BETWEEN :du AND :au");
                        $req->execute([
                            'du' => $time_debut,
                            'au' => $time_fin,
                            'id' => $id_type_dechet
                        ]);
                        $vendus_pesses = $req->fetch();

                        $Mtpe = (double) $vendus_pesses['somme_pesee_ventes'];
                        $Ntpe = (int) $vendus_pesses['nb_pesees_ventes'];
                        $Notpe = (int) $vendus_pesses['quantite_pesee_vendu'];

                        $req = $bdd->prepare("SELECT
                                              SUM(vendus.quantite) as ventes_quantite
                                              FROM vendus, ventes
                                              WHERE prix > 0
                                              AND vendus.id_type_dechet = :id
                                              AND DATE(vendus.timestamp)
                                              BETWEEN :du AND :au
                                              AND ventes.id = vendus.id_vente");
                        $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id']));
                        $donnees = $req->fetch();
                        // on determine le nombre d'objets vendus
                        $ov = (int) $donnees['ventes_quantite'];

                        // on determine la masse moyenne d'un objet dans toute la base (pour le type d'objet en cours) = $Mm
                        $req = $bdd->prepare("SELECT
                                                  AVG(pesees_vendus.masse) as moy_masse_vente
                                                  FROM pesees_vendus , vendus
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND pesees_vendus.masse > 0
                                                  AND vendus.id_type_dechet = :id");

                        $req->execute(array('id' => $donnees2['id']));
                        $moy_masse_vente = (double) $donnees['moy_masse_vente'];
                        $donnees = $req->fetch();
                        // echo "toto".$Mm."toto";

                        /*
                          estimation de la masse totale vendue sur la periode pour tout les points de vente
                          masse moyenne d'un objet dans toute la base (pour le type d'objet en cours) = $Mm
                          nombre d'objets vendus (tout types confondus) = $Nt
                          nombre d'objets pesées sur la periode = $Np
                          masse totale d'objets peses sur cette periode = $Mtpe
                          nombre de pesées sur la periode pour le type d'objet = $Ntpe
                          nombre d'objets pesés sur la periode pour le type d'objet = $Notpe
                          nombre d'objets vendus sur la periode pour le type d'objet = $ov

                          if($ov == $Notpe)
                          {
                          $mtee = $Mtpe;
                          $certitude = 100;
                          }
                          else
                          {
                          $mtee = (($Mm*$ov)-($Mm*$Np))+$Mtpe;
                          $certitude = 0;
                          }
                         */

//$mtee = round((($Mm*$Nt)-($Mm*$Mp))+$Mtpe, 2);
                        if ($ov == $Notpe) {
                          $mtee = $Mtpe;
                          $certitude = 100;
                        } else {
                          $mtee = (($moy_masse_vente * $ov) - ($moy_masse_vente *
                            $Np)) + $Mtpe;
                          $certitude = round(($Notpe / $ov) * 100, 2);
                        }
                        ?>
                        <tr>
                          <th scope="row"> <a href=" jours.php?date1=<?php echo $_GET['date1'] ?>&date2=<?php echo $_GET['date2'] ?>&type=<?php echo $donnees2['id'] ?>" > <?php echo $donnees2['nom'] ?> </a></th>
                          <td><?php echo($cd); ?>€</td>
                          <td><?php echo round($Mtpe, 2); ?> Kgs.</td>
                          <td><?php echo round($Ntpe, 2); ?></td>
                          <td><?php echo $Notpe; ?></td>
                          <td><?php echo($ov); ?></td>
                          <td>
                            <?php
                            echo round($mtee, 2) . " Kgs.";
                            ?>
                          </td>
                          <td><?php echo round(($cd / $mtee) * 1000, 2) . " €"; ?>
                          </td>
                          <td><?php
//on traduit le pourcentage en valeur de vert 100% = tout vert
                            $Gvalue = round($certitude * 2.55, 0);
//on traduit le pourcentage en valeur de rouge 0% = tout rouge
                            $Rvalue = round(255 - $Gvalue, 0);
                            ?>
                            <span class='badge' id='Bcertitude' style='background-color: RGB(<?php echo $Rvalue ?>,<?php echo $Gvalue ?>,0);'>
                              <?php echo $certitude . "%"; ?>
                            </span>
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
                  <div  id="graphMV" style="height: 180px;"></div>
                </div>
              </div>
              <?php
            }
          } else {
            //// si numero ==! 0
// On on verifie le chiffre total degagé
            $req = $bdd->prepare('SELECT SUM(vendus.prix * vendus.quantite) AS total FROM vendus
   WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au AND vendus.prix > 0');
            $req->execute(array('du' => $time_debut, 'au' => $time_fin));
            $donnees = $req->fetch();
            $mtotcolo = $donnees['total'];

            if ($mtotcolo == 0) {
              ?>
              <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
              <?php
            } else {
              // On recupère tout le contenu de la table point de vente
              $req = $bdd->prepare("SELECT SUM(vendus.prix * vendus.quantite) AS total
                                    FROM vendus ,ventes
                                    WHERE DATE(ventes.timestamp) BETWEEN :du AND :au AND ventes.id_point_vente  = :numero
                                    AND ventes.id = vendus.id_vente AND vendus.prix > 0");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $donnees = $req->fetch();
              $mtotcolo = $donnees['total'];

              // on determine le nombre d'objets vendus
              $req = $bdd->prepare("SELECT SUM(vendus.quantite)
                             FROM vendus, ventes
                             WHERE vendus.prix > 0
                             AND DATE(vendus.timestamp)
                             BETWEEN :du AND :au
                             AND ventes.id_point_vente = :numero
                             AND ventes.id = vendus.id_vente");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $sum_quantite_vendus = $req->fetch(PDO::FETCH_ASSOC)['SUM(vendus.quantite)'];

              // on determine le nombre de ventes
              $req = $bdd->prepare("SELECT COUNT(DISTINCT(ventes.id))
                                    FROM ventes ,vendus
                                    WHERE vendus.id_vente = ventes.id AND DATE(vendus.timestamp)
                                    BETWEEN :du AND :au
                                    AND vendus.prix > 0
                                    AND ventes.id_point_vente = :numero");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $nb_vendus = $req->fetch(PDO::FETCH_ASSOC)['COUNT(DISTINCT(ventes.id))'];

              // on determine le nombre d'objets remboursés
              $req = $bdd->prepare("SELECT SUM(vendus.quantite) as nb_obj_remboursement
                        FROM vendus,ventes
                        WHERE vendus.remboursement > 0
                        AND DATE(vendus.timestamp)
                        BETWEEN :du AND :au
                        AND ventes.id_point_vente = :numero
                        AND ventes.id = vendus.id_vente");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $nb_obj_reboursement = $req->fetch()['nb_obj_remboursement'];

              // on determine le nombre de remboursements
              $req = $bdd->prepare('SELECT COUNT(DISTINCT(ventes.id)) as nb_remboursement
                          FROM ventes ,vendus
                          WHERE vendus.id_vente = ventes.id
                          AND DATE(vendus.timestamp)
                          BETWEEN :du AND :au
                          AND vendus.remboursement > 0
                          AND ventes.id_point_vente = :numero');
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $nb_remboursement = $req->fetch()['nb_remboursement'];

              // On recupère tout le contenu de la table point de vente
              $req = $bdd->prepare("SELECT SUM(vendus.remboursement) AS total
                          FROM vendus,ventes
                          WHERE  DATE(vendus.timestamp)
                          BETWEEN :du AND :au
                          AND ventes.id_point_vente = :numero
                          AND ventes.id = vendus.id_vente ");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $donnees = $req->fetch();
              $mtotcolo2 = $donnees['total'];

              $req = $bdd->prepare("SELECT SUM(pesees_vendus.masse)
              FROM pesees_vendus , vendus ,ventes
              WHERE pesees_vendus.id_vendu = vendus.id AND vendus.id_vente = ventes.id
              AND DATE(vendus.timestamp) BETWEEN :du AND :au AND ventes.id_point_vente  = :numero ");
              $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
              $donnees = $req->fetch();
              $Mtpe = $donnees['SUM(pesees_vendus.masse)'];
              ?>
              <div class="row">
                <div class="col-md-6">
                  <table class='table table-hover'>
                    <tbody>
                      <tr>
                        <td>-Chiffre total dégagé  :</td>
                        <td><?php echo($mtotcolo); ?> €</td>
                      </tr>
                      <?php ?>
                      <tr>
                        <td>-Nombre d'objets vendus : </td>
                        <td><?php echo($sum_quantite_vendus); ?></td>
                      </tr>
                      <tr>
                        <td>-nombre de ventes : </td>
                        <td><?php echo($nb_vendus); ?></td>
                      </tr>
                      <tr>
                        <td>-Panier moyen : </td>
                        <td><?php echo($mtotcolo / $nb_vendus); ?> €</td>
                      </tr>
                      <tr>
                        <td>-nombre d'objets remboursés : </td>
                        <td>
                          <?php
                          if ($nb_obj_reboursement == 0) {
                            echo('-');
                          } else {
                            echo(intval($nb_obj_reboursement));
                          }
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>-nombre de remboursemments : </td>
                        <td>
                          <?php
                          if ($nb_remboursement == 0) {
                            echo($nb_remboursement);
                          } else {
                            echo($nb_remboursement);
                          }
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>-somme remboursée :</td>
                        <?php
                        ?>
                        <td>
                          <?php
                          if ($mtotcolo2 == 0) {
                            echo('-');
                          } else {
                            echo($mtotcolo2 . ' €');
                          }
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>-masse pesée en caisse :</td>
                        <td>
                          <?php
                          if (intval($Mtpe) == 0) {
                            echo '-';
                          } else {
                            echo $Mtpe;
                          }
                          ?> Kgs.</td>
                      </tr>
                    </tbody>
                  </table>
                  <?php
                  // Tableau de recap du Chiffre d'Affaire par mode de paiement
                  // Utile pour vérifier le fond de caisse en fin de vente
                  // Equivalent de la touche 'Z' sur une caisse enregistreuse

                  $sql = file_get_contents('../mysql/recap_CA_par_mode_paiement.sql');
                  $req = $bdd->prepare($sql);
                  $req->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));
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
                      <?php while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                          <td><?php echo($ligne['moyen']); ?> </td>
                          <td><?php echo($ligne['quantite_vendue']); ?></td>
                          <td><?php echo($ligne['total']); ?> €</td>
                          <td><?php echo($ligne['remboursement']); ?> €</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>

                  <h4>Chiffre dégagé par type d'objet :</h4>
                  <div id="graphPV" style="height: 180px;"></div>
                </div>
                <div class="col-md-5 col-md-offset-1">
                  <h2>chiffre de caisse : <?php echo $mtotcolo - $mtotcolo2 . " €"; ?></h2>
                  <br>
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
                      $reponse2->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));

                      $req_ventes = $bdd->prepare("SELECT SUM(vendus.quantite) as total
                                                   FROM vendus, ventes
                                                   WHERE prix > 0
                                                   AND vendus.id_type_dechet = :id
                                                   AND DATE(vendus.timestamp)
                                                   BETWEEN :du AND :au
                                                   AND ventes.id = vendus.id_vente
                                                   AND ventes.id_point_vente = :numero");

                      $req_somme_remb = $bdd->prepare("SELECT SUM(vendus.remboursement) AS total
                                                    FROM vendus, ventes
                                                    WHERE DATE(vendus.timestamp)
                                                    BETWEEN :du AND :au
                                                    AND vendus.id_type_dechet = :id
                                                    AND ventes.id = vendus.id_vente
                                                    AND ventes.id_point_vente  = :numero");

                      $req_remb = $bdd->prepare("SELECT SUM(vendus.quantite) as total
                                                FROM vendus, ventes
                                                WHERE remboursement > 0
                                                AND vendus.id_type_dechet = :id
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au AND ventes.id_point_vente = :numero
                                                AND ventes.id = vendus.id_vente");

                      // on determine la masse d'objets pesés
                      $reqMtpe = $bdd->prepare('SELECT SUM(pesees_vendus.masse)
                                                  FROM pesees_vendus , vendus ,ventes
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp)
                                                  BETWEEN :du AND :au
                                                  AND ventes.id_point_vente = :numero
                                                  AND ventes.id = vendus.id_vente');
                      while ($donnees2 = $reponse2->fetch()) {
                        // on determine le nombre d'objets vendus
                        $req_ventes->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                        $donnees = $req_ventes->fetch(PDO::FETCH_ASSOC);
                        $Nt = $donnees['total'];

                        // on determine la somme des remboursements
                        $req_somme_remb->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                        $somme_remb = $req_somme_remb->fetch(PDO::FETCH_ASSOC)['total'];

                        // on determine le nombre d'objets remboursés
                        $req_remb->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                        $nb_remb = $req_remb->fetch(PDO::FETCH_ASSOC)['total'];

                        $reqMtpe->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                        $Mtpe = $reqMtpe->fetch(PDO::FETCH_ASSOC)['SUM(pesees_vendus.masse)'];
                        ?>
                        <tr>
                          <th scope="row">
                            <a href=" jours.php?date1=<?php echo $_GET['date1'] ?>&date2=<?php echo $_GET['date2'] ?>&type=<?php echo $donnees2['id'] ?>" > <?php echo $donnees2['nom'] ?></a>
                          </th>
                          <td><?php echo $donnees2['total'] . " €" ?></td>
                          <td><?php echo $Nt; ?></td>
                          <td>
                            <?php
                            if ($somme_remb == 0) {
                              echo "-";
                            } else {
                              echo $somme_remb . " €";
                            }
                            ?>
                          </td>
                          <td>
                            <?php
                            if ($nb_remb == 0) {
                              echo "-";
                            } else {
                              echo intval($nb_remb);
                            }
                            ?>
                          </td>
                          <td><?php echo round($Mtpe, 2) . " Kgs."; ?></td>
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
                                                    type_dechets.nom ,SUM(vendus.prix * vendus.quantite) as total
                                                    FROM type_dechets , vendus, ventes
                                                    WHERE vendus.id_vente = ventes.id
                                                    AND type_dechets.id = vendus.id_type_dechet
                                                    AND DATE(vendus.timestamp) BETWEEN :du AND :au AND ventes.id_point_vente  = :numero');
                        $reponse2->execute(array('du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']));

                        // on determine le nombre de pesés
                        $reqNtpe = $bdd->prepare("SELECT COUNT(DISTINCT(pesees_vendus.id))
                                                    FROM pesees_vendus , vendus ,ventes
                                                    WHERE pesees_vendus.id_vendu = vendus.id
                                                    AND vendus.id_type_dechet = :id
                                                    AND DATE(vendus.timestamp)
                                                    BETWEEN :du AND :au
                                                    AND vendus.id_vente = ventes.id
                                                    AND ventes.id_point_vente = :numero ");

                        $reqOv = $bdd->prepare("SELECT SUM(vendus.quantite)
                                                FROM vendus,ventes WHERE prix > 0
                                                AND vendus.id_type_dechet = :id
                                                AND DATE(vendus.timestamp)
                                                BETWEEN :du AND :au
                                                AND ventes.id = vendus.id_vente
                                                AND ventes.id_point_vente = :numero ");

                        $reqNotpe = $bdd->prepare("SELECT SUM(pesees_vendus.quantite)
                                                          FROM pesees_vendus, vendus ,ventes
                                                          WHERE pesees_vendus.id_vendu = vendus.id
                                                          AND vendus.id_type_dechet = :id
                                                          AND DATE(vendus.timestamp)
                                                          BETWEEN :du AND :au
                                                          AND vendus.id_vente = ventes.id
                                                          AND ventes.id_point_vente = :numero ");

                        // On determine la masse moyenne d'un objet dans toute la base (pour le type d'objet en cours) = $Mm
                        $reqMm = $bdd->prepare("SELECT AVG(pesees_vendus.masse)
                                              FROM pesees_vendus, vendus
                                              WHERE pesees_vendus.id_vendu = vendus.id
                                              AND pesees_vendus.masse > 0
                                              AND vendus.id_type_dechet = :id ");

                        $reqMtpe = $bdd->prepare("SELECT SUM(pesees_vendus.masse)
                                                  FROM pesees_vendus , vendus ,ventes
                                                  WHERE pesees_vendus.id_vendu = vendus.id
                                                  AND vendus.id_type_dechet = :id
                                                  AND DATE(vendus.timestamp)
                                                  BETWEEN :du AND :au AND vendus.id_vente = ventes.id
                                                  AND ventes.id_point_vente  = :numero ");

                        while ($donnees2 = $reponse2->fetch()) {
                          $reqNtpe->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                          $Ntpe = $req->fetch(PDO::FETCH_ASSOC)['COUNT(DISTINCT(pesees_vendus.id))'];
                          // on determine le nombre d'objets vendus

                          $reqOv->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                          $ov = (int) $reqOv->fetch()['SUM(vendus.quantite)'];

                          // on determine le nombre d'objets pesés
                          $reqNotpe->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                          $Notpe = (int) $reqNotpe->fetch(PDO::FETCH_ASSOC)['SUM(pesees_vendus.quantite)'];

                          $reqMm->execute(array('id' => $donnees2['id']));
                          $Mm = (double) $reqMm->fetch(PDO::FETCH_ASSOC)['AVG(pesees_vendus.masse)'];

                          // on determine la masse d'objets pesés
                          $reqMtpe->execute(array('du' => $time_debut, 'au' => $time_fin, 'id' => $donnees2['id'], 'numero' => $_GET['numero']));
                          $Mtpe = $reqMtpe->fetch(PDO::FETCH_ASSOC)['SUM(pesees_vendus.masse)'];

                          $cd = $donnees2['total'];

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
                              <a href=" jours.php?date1=<?php echo $_GET['date1'] ?>&date2=<?php echo $_GET['date2'] ?>&type=<?php echo $donnees2['id'] ?>" > <?php echo $donnees2['nom'] ?> </a>
                            </th>

                            <td><?php echo $cd; ?> €</td>
                            <td><?php echo round($Mtpe, 2) . " Kgs."; ?></td>
                            <td><?php echo round($Ntpe, 2); ?></td>
                            <td><?php echo $Notpe; ?></td>
                            <td><?php echo $ov; ?></td>
                            <td><?php /* echo $Mm;  echo round($mtee, 2); */ echo "Pas Implemente"; ?> Kgs.</td>
                            <td><?php /* echo round(($cd / $mtee) * 1000, 2); */ echo"Pas Implemente"; ?> €</td>
                            <td><?php /* echo $certitude; */ echo 'pas implemente!' ?> %</td>
                          </tr>
                          <?php
                        }
                        $reponse2->closeCursor(); // Termine le traitement de la requête
                        ?>

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
                    <br>
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
  </div>
  <br>
  </div>
  </div>
  <?php
  if ($_GET['numero'] > 0) {
    $stmt = $bdd->prepare('SELECT type_dechets.couleur, type_dechets.nom, SUM(pesees_vendus.masse) as somme
                    FROM type_dechets, vendus, pesees_vendus, ventes
                    WHERE type_dechets.id = vendus.id_type_dechet
                    AND vendus.id = pesees_vendus.id_vendu
                    AND DATE(vendus.timestamp)
                    BETWEEN :du AND :au AND vendus.prix > 0
                    AND vendus.id_vente = ventes.id
                    AND ventes.id_point_vente  = :numero
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
    ;
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
    function $_GET(param) {
      var vars = {};
      window.location.href.replace(
              /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
              (m, key, value) => { // callback
        vars[key] = value !== undefined ? value : '';
      }
      );

      if (param) {
        return vars[param] ? vars[param] : null;
      } else {
        return vars;
      }
    }

    $(document).ready(() => {
      function cb(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
        $('#reportrange span').html(start.format('DD, MMMM, YYYY') + ' - ' + end.format('DD, MMMM, YYYY'));
      }
      ;

      const startDate = moment($_GET('date1'), 'DD-MM-YYYY');
      const endDate = moment($_GET('date2'), 'DD-MM-YYYY');

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
          "Aujoud'hui": [moment(), moment()],
          'hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          '7 derniers jours': [moment().subtract(6, 'days'), moment()],
          '30 derniers jours': [moment().subtract(29, 'days'), moment()],
          'Ce mois': [moment().startOf('month'), moment().endOf('month')],
          'Le mois deriner': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
          monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
          firstDay: 1
        }
      };

      $('#reportrange').daterangepicker(optionSet1, cb);
      $('#reportrange span').html(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));

      $('#reportrange').on('show.daterangepicker', () => {
        console.log("show event fired");
      });

      $('#reportrange').on('hide.daterangepicker', () => {
        console.log("hide event fired");
      });

      $('#reportrange').on('apply.daterangepicker', (ev, picker) => {
        console.log("apply event fired, start/end dates are "
                + picker.startDate.format('DD MM, YYYY')
                + " to "
                + picker.endDate.format('DD MM, YYYY')
                );
        window.location.href = `bilanv.php?date1=${picker.startDate.format('DD-MM-YYYY')}&date2=${picker.endDate.format('DD-MM-YYYY')}&numero=<?php echo $_GET['numero']; ?>`;
      });

      $('#reportrange').on('cancel.daterangepicker', (ev, picker) => {
        console.log("cancel event fired");
      });

      $('#options1').click(() => {
        $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
      });

      $('#options2').click(() => {
        $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
      });

      $('#destroy').click(() => {
        $('#reportrange').data('daterangepicker').remove();
      });

      const dataMv = <?php echo(json_encode($graphMv, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT)); ?>;
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

      const dataPv = <?php echo(json_encode($graphPV, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT)); ?>;
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

    });
  </script>
  <?php
  include "pied_bilan.php";
} else {
  header('Location: ../moteur/destroy.php');
}
