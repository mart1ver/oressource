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

/**
 * Fonction auxillière servant à crée un nouvel `array` indexé par les `$key` de `$a`.
 * Le but est de pouvoir facilement rechercher des élèments dans un array par leur id.
 * Avec une compléxité algorithmique de O(n log(n)) et non O(n) si on à un simple tableau|liste.
 *
 * Exemple:
 * ```
 * $b = [ ['id' => 1, 'data' => 'foo' ]], ['id' => 10, 'data' => 'bar']];
 * echo map_by($b, 'id');
 * # [ 1 => ['id' => 1, 'data' => 'foo'], 10 => ['id' => 10, 'data' => 'bar']]
 * ```
 * @param array $a Array contenant des array avec toujours la clef $key dedans.
 * @param mixed $key Clé sur laquelle nous désirons indexé notre array de retour
 * @return array Array indéxé par la la key donnée en paramètre
 */
function map_by(array $a, $key): array {
  return array_reduce($a, function ($acc, $e) use ($key) {
    $acc[$e[$key]] = $e;
    return $acc;
  }, []);
}

function filter_visibles(array $a): array {
  return array_values(array_filter($a, function (array $e) {
      return $e['visible'];
    }));
}

function fetch_all(string $sql, PDO $bdd): array {
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

/*
 * Attention à bien nommer dans la chaine $sql le champ de l'id ':id'.
 */
function fetch_id(PDO $bdd, string $sql, int $id): array {
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function fetch_all_id(PDO $bdd, string $sql, int $id): array {
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $r;
}

function objet_id_dechet(PDO $bdd, int $id_dechet): array {
  return fetch_all_id($bdd, 'SELECT * FROM grille_objets WHERE id_type_dechet = :id', $id_dechet);
}

function objet_id(PDO $bdd, int $id_obj): array {
  return fetch_id($bdd, 'SELECT * FROM grille_objets WHERE id = :id', $id_obj);
}

function objets(PDO $bdd): array {
  $sql = 'SELECT *
  from grille_objets
  order by nom';
  return fetch_all($sql, $bdd);
}

function utilisateurs_id(PDO $bdd, int $id): array {
  $sql = 'SELECT
    id,
    prenom,
    pass,
    mail,
    nom,
    niveau
  FROM utilisateurs
  WHERE utilisateurs.id = :id';
  return fetch_id($bdd, $sql, $id);
}

function utilisateurs(PDO $bdd): array {
  $sql = 'SELECT
    id,
    prenom,
    mail,
    nom,
    niveau
    FROM utilisateurs';
  return fetch_all($sql, $bdd);
}

function utilisateur_insert(PDO $bdd, array $utilisateur): int {
  global $_SESSION;
  $sql = 'INSERT INTO utilisateurs (
      nom,
      prenom,
      mail,
      pass,
      niveau,
      id_createur,
      id_last_hero
      ) VALUES (
        :nom,
        :prenom,
        :mail,
        :pass,
        :niveau,
        :id_createur,
        :id_createur1)';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':nom', $utilisateur['nom'], PDO::PARAM_STR);
  $stmt->bindParam(':prenom', $utilisateur['prenom'], PDO::PARAM_STR);
  $stmt->bindParam(':mail', $utilisateur['mail'], PDO::PARAM_STR);
  $stmt->bindParam(':pass', $utilisateur['pass'], PDO::PARAM_STR);
  $stmt->bindParam(':niveau', $utilisateur['niveau'], PDO::PARAM_STR);
  $stmt->bindParam(':id_createur', $_SESSION['id'], PDO::PARAM_INT);
  $stmt->bindParam(':id_createur1', $_SESSION['id'], PDO::PARAM_INT);
  $stmt->execute();
  $id = (int) $bdd->lastInsertId();
  $stmt->closeCursor();
  return $id;
}

function utilisateur_update(PDO $bdd, array $utilisateur, int $id) {
  $sql = 'UPDATE utilisateurs SET
    nom = :nom,
    prenom = :prenom,
    mail = :mail,
    niveau = :niveau,
    id_last_hero = :id_editeur
  WHERE
    id = :id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->bindParam(':nom', $utilisateur['nom'], PDO::PARAM_STR);
  $stmt->bindParam(':prenom', $utilisateur['prenom'], PDO::PARAM_STR);
  $stmt->bindParam(':mail', $utilisateur['mail'], PDO::PARAM_STR);
  $stmt->bindParam(':niveau', $utilisateur['niveau'], PDO::PARAM_STR);
  $stmt->bindParam(':id_editeur', $_SESSION['id'], PDO::PARAM_INT);
  $stmt->execute();
  $stmt->closeCursor();
}

function sortie_id(PDO $bdd, int $id): array {
  $sql = 'SELECT * FROM sorties WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function convention_sortie(PDO $bdd): array {
  $sql = 'SELECT id, nom, couleur, description, timestamp, visible
  FROM conventions_sorties
  ORDER BY nom';
  return fetch_all($sql, $bdd);
}

function convention_sortie_by_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, couleur, visible, description, timestamp, visible
  FROM conventions_sorties
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function points_collecte_id(PDO $bdd, int $id): array {
  $sql = 'SELECT pesee_max, nom
  FROM points_collecte
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function points_sorties_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, pesee_max, nom, adresse, visible
  FROM points_sortie
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function points_collectes(PDO $bdd): array {
  $sql = 'SELECT id, nom, adresse, couleur, pesee_max,
    visible, timestamp, commentaire, pesee_max
  FROM points_collecte';
  return fetch_all($sql, $bdd);
}

function points_sorties(PDO $bdd): array {
  $sql = 'SELECT id, nom, adresse, couleur,
    visible, timestamp, commentaire, pesee_max
  FROM points_sortie';
  return fetch_all($sql, $bdd);
}

function points_ventes(PDO $bdd): array {
  $sql = 'SELECT id, nom, adresse, couleur, visible
  FROM points_vente';
  return fetch_all($sql, $bdd);
}

function points_ventes_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, adresse
  FROM points_vente
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function types_contenants(PDO $bdd): array {
  $sql = 'SELECT
    id, masse, nom,
    couleur, visible, description,
    timestamp
  from type_contenants
  order by nom';
  return fetch_all($sql, $bdd);
}

function types_contenants_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, masse, nom, couleur, visible, description, timestamp
  FROM type_contenants
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function localites(PDO $bdd): array {
  $sql = 'SELECT id, nom, couleur, visible FROM localites';
  return fetch_all($sql, $bdd);
}

function localites_id(PDO $bdd, int $id): array {
  $sql = 'SELECT
    id, nom, couleur,
    visible, commentaire,
    relation_openstreetmap as lien
  FROM localites
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function types_poubelles(PDO $bdd): array {
  $sql = 'SELECT id, nom, masse_bac, couleur, visible
  FROM types_poubelles';
  return fetch_all($sql, $bdd);
}

function types_poubelles_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, masse_bac, couleur, ultime, description
  FROM types_poubelles
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function types_dechets_evac(PDO $bdd): array {
  $sql = 'SELECT id, nom, couleur, visible, description, timestamp
  FROM type_dechets_evac';
  return fetch_all($sql, $bdd);
}

function types_dechets_evac_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, couleur, description, visible
  FROM type_dechets_evac
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function types_dechets(PDO $bdd): array {
  $sql = 'SELECT id, nom, couleur, description, visible, timestamp
  FROM type_dechets';
  return fetch_all($sql, $bdd);
}

function types_dechets_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, couleur, description, visible
  FROM type_dechets
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function types_sorties(PDO $bdd): array {
  $sql = 'SELECT id, nom, couleur, visible, description, timestamp
  from type_sortie
  order by nom';
  return fetch_all($sql, $bdd);
}

function types_collectes(PDO $bdd): array {
  $sql = 'SELECT id, nom, couleur, visible, timestamp, description
  from type_collecte
  order by nom';
  return fetch_all($sql, $bdd);
}

function types_collectes_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, description, couleur, visible
  FROM type_collecte
  WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

function filieres_sorties(PDO $bdd): array {
  $sql = 'SELECT id, nom, visible, id_type_dechet_evac, description,
    couleur, timestamp, last_hero_timestamp, id_createur, id_last_hero
  from filieres_sortie
  order by nom';
  return array_map(function($filiere) {
    $a = array_filter(explode('a', $filiere['id_type_dechet_evac']), function ($e): bool { return $e !== ''; });
    $b = array_map(function (int $e): int { return $e; }, $a);
    $filiere['types_dechets'] = array_values($b);
    $filiere['accepte_type_dechet'] = array_flip($b);
    return $filiere;
  }, fetch_all($sql, $bdd));
}

function filieres_sorties_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, visible, id_type_dechet_evac, description, couleur FROM filieres_sortie WHERE id = :id';
  $r = fetch_id($bdd, $sql, $id);
  $r['accepte_type_dechet'] = array_flip(array_filter(explode('a', $r['id_type_dechet_evac']),
    function ($e) { return $e !== ''; }));
  return $r;
}

function nb_categories_dechets_evac(PDO $bdd): int {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM type_dechets_evac LIMIT 1');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return $nombre_categories;
}

function nb_categories_dechets_item(PDO $bdd): int {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM type_dechets');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return (int) $nombre_categories;
}

function nb_categories_poubelles(PDO $bdd): int {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM types_poubelles LIMIT 1');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return (int) $nombre_categories;
}

function collecte_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id,
    timestamp, id_type_collecte,
    localisation, id_point_collecte,
    commentaire, id_createur,
    id_last_hero, last_hero_timestamp
    From collecte
    Where id = :id';
  return fetch_id($bdd, $sql, $id);
}

function moyens_paiements(PDO $bdd): array {
  $sql = 'SELECT id, nom, couleur, visible, description, timestamp
  FROM moyens_paiement';
  return fetch_all($sql, $bdd);
}

function structure(PDO $bdd): array {
  $sql = 'SELECT id_localite, tva_active, taux_tva, nom,
                siret, telephone, mail, description, cr, adresse, texte_adhesion, lot, viz,
                nb_viz, saisiec, affsp, affss, affsr,
                affsd, affsde, pes_vente, force_pes_vente
          FROM description_structure';
  $req = $bdd->prepare($sql);
  $req->execute();
  $result = $req->fetch(PDO::FETCH_ASSOC);
  $req->closeCursor();
  return $result;
}

function structure_update(PDO $bdd, array $structure) {
  $sql = 'UPDATE description_structure
    SET nom = :nom,
    adresse = :adresse,
    id_localite = :id_localite,
    description = :description,
    siret = :siret,
    telephone = :telephone,
    mail = :mail,
    taux_tva = :taux_tva,
    tva_active = :tva_active,
    cr = :cr,
    lot = :lot,
    viz = :viz,
    nb_viz = :nb_viz,
    saisiec = :saisiec,
    affsp = :affsp,
    affss = :affss,
    affsr = :affsr,
    affsd = :affsd,
    affsde = :affsde,
    pes_vente = :pes_vente,
    force_pes_vente = :force_pes_vente
    WHERE id = :id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':nom', $structure['nom'], PDO::PARAM_STR);
  $stmt->bindValue(':adresse', $structure['adresse'], PDO::PARAM_STR);
  $stmt->bindValue(':id_localite', $structure['id_localite'], PDO::PARAM_INT);
  $stmt->bindValue(':description', $structure['description'], PDO::PARAM_STR);
  $stmt->bindValue(':siret', $structure['siret'], PDO::PARAM_STR);
  $stmt->bindValue(':telephone', $structure['telephone'], PDO::PARAM_STR);
  $stmt->bindValue(':mail', $structure['mail'], PDO::PARAM_STR);
  $stmt->bindValue(':taux_tva', $structure['taux_tva'], PDO::PARAM_STR);
  $stmt->bindValue(':tva_active', $structure['tva_active'], PDO::PARAM_INT);
  $stmt->bindValue(':cr', $structure['cr'], PDO::PARAM_STR);
  $stmt->bindValue(':lot', $structure['lot'], PDO::PARAM_INT);
  $stmt->bindValue(':viz', $structure['viz'], PDO::PARAM_INT);
  $stmt->bindValue(':nb_viz', $structure['nb_viz'], PDO::PARAM_INT);
  $stmt->bindValue(':saisiec', $structure['saisiec'], PDO::PARAM_INT);
  $stmt->bindValue(':affsp', $structure['affsp'], PDO::PARAM_INT);
  $stmt->bindValue(':affss', $structure['affss'], PDO::PARAM_INT);
  $stmt->bindValue(':affsr', $structure['affsr'], PDO::PARAM_INT);
  $stmt->bindValue(':affsd', $structure['affsd'], PDO::PARAM_INT);
  $stmt->bindValue(':affsde', $structure['affsde'], PDO::PARAM_INT);
  $stmt->bindValue(':pes_vente', $structure['pes_vente'], PDO::PARAM_INT);
  $stmt->bindValue(':force_pes_vente', $structure['force_pes_vente'], PDO::PARAM_INT);
  $stmt->bindValue(':id', $structure['id'], PDO::PARAM_INT);
  $stmt->execute();
  $stmt->closeCursor();
}

// Return a valid user or an exception.
function login_user(PDO $bdd, string $email, string $password): array {
  $passmd5 = md5($password);
  $req = $bdd->prepare('SELECT id, niveau, nom, prenom, mail
                        FROM utilisateurs
                        WHERE mail = :mail
                          AND pass = :pass
                          AND (pass IS NOT NULL)');
  $req->bindValue(':mail', $email, PDO::PARAM_STR);
  $req->bindValue(':pass', $passmd5, PDO::PARAM_INT);
  $req->execute();
  $user = $req->fetch(PDO::FETCH_ASSOC);
  $req->closeCursor();
  if ($user) {
    return $user;
  }
  throw new Exception('Mot de passe ou nom de compte invalide.');
}

/// Cas particulier de data_graphs_from_bilan pour bilanc.php
function data_graphs(array $data): array {
  return data_graphs_from_bilan($data, 'somme');
}

/**
 * Renvoie les donnees neccessaire a morris.js pour le tableau de bord.
 * @global PDO $bdd Connection PDO valide.
 * @param string $key clef pour le champ 'value' de l'array produit.
 * @return array Correspondant aux donnees pour morris.js.
 */
function data_graphs_from_bilan(array $bilan, string $key): array {
  $data = [];
  $colors = [];
  foreach ($bilan as $_ => $iter) {
    array_push($data, ['value' => $iter[$key], 'label' => $iter['nom']]);
    array_push($colors, $iter['couleur']);
  }
  return ['data' => $data, 'colors' => $colors];
}

/// Permet d'obtenir le détail d'une vente ou
/// d'un remboursement identifié par son Id vente.
function vendu_by_id_vente(PDO $bdd, int $id_vente): array {
  $req = $bdd->prepare('SELECT
    vendus.id,
    vendus.id_vente,
    vendus.timestamp,
    vendus.quantite,
    vendus.prix,
    vendus.remboursement,
    vendus.id_createur,
    vendus.id_last_hero,
    vendus.last_hero_timestamp,
    type_dechets.nom type,
    CASE WHEN vendus.id_objet > 0 THEN grille_objets.nom ELSE "autre" END objet,
    pesees_vendus.masse
  FROM vendus
  INNER JOIN type_dechets
  ON type_dechets.id = vendus.id_type_dechet
  left JOIN grille_objets
  ON grille_objets.id = vendus.id_objet
  LEFT JOIN pesees_vendus
  ON pesees_vendus.id = vendus.id_vente
  WHERE vendus.id_vente = :id_vente');
  $req->bindParam(':id_vente', $id_vente, PDO::PARAM_INT);
  $req->execute();
  $vendus = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();
  return $vendus;
}

/// HACKME: Fonction contenant le code SQL pour gérer les lots.
/// TODO: Trouver une meilleure méthode.
function vendus_case_lot_unit(): string {
  return "case when vendus.lot > 0
  then vendus.prix
  else vendus.prix * vendus.quantite end";
}

/** Tableau de recap du Chiffre d'Affaire par mode de paiement
 * Utile pour vérifier le fond de caisse en fin de vente
 * Equivalent de la touche 'Z' sur une caisse enregistreuse
 */
function chiffre_affaire_mode_paiement(PDO $bdd, string $start,
  string $stop, int $id_point_vente = 0): array {
  $cond = ($id_point_vente > 0 ? " AND ventes.id_point_vente = $id_point_vente " : ' ');
  $sql = "SELECT
    ventes.id_moyen_paiement AS id_moyen,
    moyens_paiement.nom AS moyen,
    COUNT(DISTINCT(ventes.id)) AS quantite_vendue,
    SUM(" . vendus_case_lot_unit(). ") AS total,
    SUM(vendus.remboursement) AS remboursement
  FROM moyens_paiement
  INNER JOIN ventes
  ON moyens_paiement.id = ventes.id_moyen_paiement
  $cond
  INNER JOIN vendus
  ON vendus.id_vente = ventes.id
  WHERE DATE(vendus.timestamp) BETWEEN :du AND :au
  GROUP BY ventes.id_moyen_paiement, moyens_paiement.nom";
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/// Fonction calculant le nombre de ventes réalisé entre la période $start et
/// $stop.
function nb_ventes(PDO $bdd, string $start, string $stop,
 int $id_point_vente = 0): int {
  $cond = ($id_point_vente > 0 ? " AND ventes.id_point_vente = $id_point_vente " : ' ');
  $sql = "SELECT
    COUNT(DISTINCT(ventes.id)) as nb_ventes
  FROM ventes
  INNER JOIN vendus
  ON ventes.id = vendus.id_vente
  AND vendus.prix >= 0
  AND vendus.remboursement = 0
    $cond
  AND DATE(vendus.timestamp)
  BETWEEN :du AND :au";
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $nb_ventes = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nb_ventes'];
  $stmt->closeCursor();
  return $nb_ventes;
}

function nb_remboursements(PDO $bdd, string $start, string $stop,
 int $id_point_vente = 0): int {
  $cond = ($id_point_vente > 0 ? " AND ventes.id_point_vente = $id_point_vente " : ' ');
  $sql = "SELECT
    COUNT(DISTINCT(ventes.id)) as nb_remb
  FROM ventes
  INNER JOIN vendus
  ON ventes.id = vendus.id_vente
  AND vendus.remboursement > 0
  AND vendus.prix = 0
    $cond
  AND DATE(vendus.timestamp)
  BETWEEN :du AND :au";
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $result = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nb_remb'];
  $stmt->closeCursor();
  return $result;
}

function viz_caisse(PDO $bdd, int $id_point_vente, int $offset): array {
  $sql = "SELECT
    ventes.id as id,
    ventes.timestamp as date_creation,
    moyens_paiement.nom as moyen,
    moyens_paiement.couleur as coul,
    ventes.commentaire as commentaire,
    ventes.last_hero_timestamp as lht,
    utilisateurs.mail as mail,
    SUM(" . vendus_case_lot_unit(). ") as credit,
    SUM(vendus.remboursement * vendus.quantite) as debit,
    SUM(vendus.quantite) as quantite
  from ventes
  inner join vendus
    on vendus.id_vente = ventes.id
    and DATE(ventes.timestamp) = DATE(NOW())
    and ventes.id_point_vente = :id_point_vente
  inner join moyens_paiement
    on ventes.id_moyen_paiement = moyens_paiement.id
  inner join utilisateurs
    on utilisateurs.id = ventes.id_createur
  group by ventes.id, ventes.timestamp, moyens_paiement.nom,
    moyens_paiement.couleur, ventes.commentaire,
    ventes.last_hero_timestamp, utilisateurs.mail
  order by ventes.timestamp desc
  limit 0, :offset";
  $reqVentes = $bdd->prepare($sql);
  $reqVentes->bindValue('id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $reqVentes->bindValue('offset', $offset, PDO::PARAM_INT);
  $reqVentes->execute();
  $resultat = $reqVentes->fetchAll(PDO::FETCH_ASSOC);
  $reqVentes->closeCursor();
  return $resultat;
}

function bilan_ventes_par_type(PDO $bdd, string $start, string $stop,
int $id_point_vente = 0): array {
  $cond = ($id_point_vente > 0 ? " AND ventes.id_point_vente = $id_point_vente " : ' ');
  $sql = "SELECT
    type_dechets.id as id,
    type_dechets.couleur as couleur,
    type_dechets.nom as nom,
    SUM(" . vendus_case_lot_unit(). ") as chiffre_degage,
    SUM(vendus.quantite) as vendu_quantite,
    SUM(vendus.remboursement) as remb_somme,
    SUM(case when vendus.remboursement > 0 then 1 else 0 end) as remb_quantite
  FROM vendus
  INNER JOIN type_dechets
  ON vendus.id_type_dechet = type_dechets.id
  INNER JOIN ventes
  ON vendus.id_vente = ventes.id
    $cond
  WHERE DATE(vendus.timestamp)
  BETWEEN :du AND :au
  GROUP BY type_dechets.id, type_dechets.couleur, type_dechets.nom";
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();

  $result = [];
  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $data['id'];
    unset($data['id']);
    $result[$id] = $data;
  }
  $stmt->closeCursor();
  return $result;
}

function bilan_ventes_pesees(PDO $bdd, string $start, string $stop,
  int $id_point_vente = 0): array {
  $cond = ($id_point_vente > 0 ? " AND ventes.id_point_vente = $id_point_vente " : ' ');
  $sql = "SELECT
      type_dechets.id as id,
      type_dechets.nom as nom,
      type_dechets.couleur as couleur,
      COUNT(DISTINCT(pesees_vendus.id)) as nb_pesees_ventes,
      COALESCE(SUM(pesees_vendus.quantite), 0) as quantite_pesee_vendu,
      COALESCE(SUM(pesees_vendus.masse), 0) as vendu_masse,
      COALESCE(AVG(pesees_vendus.masse), 0) as moy_masse_vente
    FROM pesees_vendus
    INNER JOIN vendus
    ON vendus.id = pesees_vendus.id
    INNER JOIN type_dechets
    ON vendus.id_type_dechet = type_dechets.id
    INNER JOIN ventes
    ON vendus.id_vente = ventes.id
      $cond
    WHERE DATE(pesees_vendus.timestamp)
    BETWEEN :du AND :au
    GROUP BY type_dechets.id, type_dechets.nom, type_dechets.couleur";
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $result = [];
  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $data['id'];
    unset($data['id']);
    $result[$id] = $data;
  }
  $stmt->closeCursor();
  return $result;
}

function bilan_ventes(PDO $bdd, string $start, string $stop,
  int $id_point_vente = 0): array {
  $cond = ($id_point_vente > 0 ? " AND ventes.id_point_vente = $id_point_vente " : ' ');
  $sql = "SELECT
    COUNT(DISTINCT(ventes.id)) as nb_ventes,
    SUM(" . vendus_case_lot_unit(). ") as chiffre_degage,
    SUM(vendus.quantite) as vendu_quantite,
    SUM(vendus.remboursement) as remb_somme,
    SUM(case when vendus.remboursement > 0 then 1 else 0 end) as remb_quantite,
    COALESCE(SUM(pesees_vendus.masse), 0) as vendu_masse
  FROM ventes
  INNER JOIN vendus
  ON vendus.id_vente = ventes.id
  LEFT JOIN pesees_vendus
  ON vendus.id = pesees_vendus.id
  WHERE DATE(ventes.timestamp) BETWEEN :du AND :au $cond";
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $bilan = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $bilan;
}
