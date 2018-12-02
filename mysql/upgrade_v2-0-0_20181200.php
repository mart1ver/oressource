<?php

require_once '../moteur/dbconfig.php';


function recover_utilisateurs(int $admin, string $table, array $fields): string {
  return ("update $table as T
  set T.$fields[0] = $admin,
      T.$fields[1] = $admin
  where not exists (select 1
  from utilisateurs U
  where U.id = T.$fields[0]);
");
}

function remove_pesees_collectes_sans_collectes(PDO $bdd) {
  $sql = "delete P from pesees_collectes as P where not exists (select 1 from collectes as C where P.id_collecte = C.id)";

  $bdd->query($sql);
}

function recover_points_ventes(PDO $bdd) {
  $sql0 = 'insert INTO points_vente (
    nom,
    commentaire,
    surface_vente,
    couleur,
    visible,
    id_createur,
    id_last_hero
    ) VALUES (
    "[Maintenance] point de vente inconnu",
    "Ce type existe afin de réguler le saisies incohérentes des anciennes bases.",
    0,
    "#000000",
    0,
    1,
    1
  );';

  $bdd->query($sql0);
  $id_point_vente_inconnu = $bdd->lastInsertId();

  $sql1 = "update ventes as V
  set V.id_point_vente = $id_point_vente_inconnu
  where not exists (select 1
  from points_vente PV
  where PV.id = V.id_point_vente)";
  $bdd->query($sql1);
}

function recover(PDO $bdd, string $database_name, int $admin) {
  $tables = $bdd->query("SELECT table_name FROM information_schema.tables
    where table_schema=\"$database_name\"");

  $fields = [ 'id_createur', 'id_last_hero'];
  foreach ($tables as $t) {
    $table = $t['table_name'];

    if ($name === NULL || $name === 'utilisateurs') {
      continue;
    }

    $sql_users = recover_utilisateurs($admin, $table, $fields);
    $bdd->query($sql_users);
    $sql_timestamp = recover_timestamp($table);
    $bdd->query($sql_timestamp);
  }
}

function recover_timestamp(string $table): string {
  return "UPDATE `$table` set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when (`last_hero_timestamp` IS NULL) OR (`last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s'))
    then `timestamp` else `last_hero_timestamp`
  end";
}

/*
 * Ce script récupére les utilisateurs supprimées et les réapparentes à un utilisateur
 * administrateur.
 * Modifier la variable $admin pour changer cet utilisateur.
 */
function main() {
  global $bdd;
  global $base;
  $database_name = $base;
  {
    $sql_files = [
      '2017-12-18_drop_adherents.sql',
      '2017-12-23_pesee_vendus.sql',
      '2017-12-24_conformite_strict_SQL.sql',
      '2017-12-24_ventes_par_lot.sql',
      '2017-12-29_remove_oui_non.sql'
    ];

    foreach ($sql_files as $file) {
      $sql = file_get_contents($file);
      $bdd->exec($sql);
    }
  }

  recover_points_ventes($bdd);

  // Note: Cas de la petite rockette
  $req = $bdd->query('select id from utilisateurs where mail = "inconnu@localhost"');
  $id = $req->fetch()['id'];
  $req->closeCursor();
  recover($bdd, $database_name, $id);


  {
    $sql = file_get_contents('2017-12-30_ajout_clef_etrangeres.sql');
    $bdd->exec($sql);
  }
}

main();
