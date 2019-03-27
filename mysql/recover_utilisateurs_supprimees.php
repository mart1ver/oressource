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
    $name = $t['table_name'];

    if ($name === NULL || $name === 'utilisateurs') {
      continue;
    }

    $sql = recover_utilisateurs($admin, $name, $fields);
    $bdd->query($sql);
  }
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

  recover_points_ventes($bdd);

  // Note: Cas de la petite rockette
  $req = $bdd->query('select id from utilisateurs where mail = "inconnu@localhost"');
  $id = $req->fetch()['id'];
  $req->closeCursor();
  recover($bdd, $database_name, $id);
}

main();
