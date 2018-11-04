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

  // Note: Cas de la petite rockette
  $admin = 1;
  recover($bdd, $database_name, $admin);
}

main();
