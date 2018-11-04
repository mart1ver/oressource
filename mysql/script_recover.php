<?php

require_once '../moteur/dbconfig.php';

function recover_utilisateurs(int $admin, string $table, string $col): string {
  return ("update $table
  set $col = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = $table.$col);\n");
}

function recover(PDO $bdd, string $database_name, int $admin) {
  $tables = $bdd->query("SELECT table_name FROM information_schema.tables
    where table_schema=$database_name");

  foreach ($tables as $t) {
    $name = $t['table_name'];
    $sql = (
      recover_utilisateurs($admin, $name, "id_createur")
    . recover_utilisateurs($admin, $name, "id_last_hero")
    );
    echo($sql);
    continue;
    $bdd->query();
  }
}


function main() {
  global $bdd;
  //
  $database_name = "oressource3";

  // Note: Cas de la petite rockette
  $admin = 1;
  recover($bdd, $database_name, $admin);
}

main();
