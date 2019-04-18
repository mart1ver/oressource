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

//redirige vers l'index dans /ifaces
header('Location: ../ifaces/');

function recover_utilisateurs($table, $col) {
  return ("(update $table
  set $col = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = $table.$col));");
}

function recover() {
  $tables = $bdd->query("SELECT table_name FROM information_schema.tables where table_schema='oressource3'");

  foreach ($tables as $t) {
    $sql = recover_utilisateurs($t, "id_createur") + recover_utilisateurs($t, "id_last_hero");
    echo $sql;
    continue;
    $bdd->query();
  }
}


function main() {
  recover();
}

main();